<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Deal;
use App\Models\LenderCommissionSchedule;

use Illuminate\Support\Facades\Log;

class Deals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deal:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try{
            Deal::query()->chunk(500, function($deals){
                    foreach($deals as $deal){
                        $lenderData = LenderCommissionSchedule::query()->where(['product_id' => $deal->product_id, 'lender_id' => $deal->lender_id])->get();
        
                        if($lenderData){
                            foreach($lenderData as $data){
                                if($data->commission_type_id == 12 && $data->per_rate){
                                    $actualAmount = $deal->actual_loan;
                                    $brokerTrailAmount = ($actualAmount * $data->per_rate / 100) / 12;
                                    Deal::query()->where('id', $deal->id)->update([
                                        "broker_est_trail" => $brokerTrailAmount
                                    ]);
                                }
                                if($data->commission_type_id == 13 && $data->per_rate){
                                    $actualAmount = $deal->actual_loan;
                                    $brokerUpfrontAmount = $actualAmount * $data->per_rate / 100;
                                    Deal::query()->where('id', $deal->id)->update([
                                        "broker_est_upfront" => $brokerUpfrontAmount
                                    ]);
                                }
        
                            }
                            
                        }
                    }
                });
            $this->info("The job is completed!");
        } catch (\Exception $e) {
            Log::error("SIMILAR_PRODUCTS_DATA_SET_ERRORS", ["ERROR" => $e->getMessage() . ". LINE" . $e->getLine() . ". FILE" . $e->getFile()]);
            }

    }
}
