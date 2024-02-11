<?php

namespace App\Console\Commands;

use App\Models\DealCommission;
use Illuminate\Console\Command;

class upfrontvariance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:upfront_variance';

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
        $dealCommissions = DealCommission::query()->where('type', 13)->get();
        foreach ($dealCommissions as $commission) {
            $receive_amount = $commission->total_amount;
            $total_amount = $commission->deal->broker_est_upfront??0;
            if ($total_amount > 0) {
                $variance = round(($receive_amount / $total_amount) * 100, 2);
                DealCommission::query()->where('id', $commission->id)->update([
                    'variance' => $variance
                ]);
            }
        }
    }
}
