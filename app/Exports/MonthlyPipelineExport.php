<?php
namespace App\Exports;

use App\Models\Deal;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class MonthlyPipelineExport implements FromView
{
    public $date_from='';
    public $status=0;
    public $date_to='';
    public $group_by="";
    public function date_from(string $date_from)
    {
        $this->date_from = $date_from;

        return $this;
    }
    public function date_to(string $date_to)
    {
        $this->date_to = $date_to;

        return $this;
    }
    public function forStatus(int $status)
    {
        $this->status = $status;

        return $this;
    }
    public function group_by(string $group_by)
    {
        $this->group_by = $group_by;

        return $this;
    }

    public function view(): View{
        $deals=Deal::select('deals.*')->with(['lender','deal_status','client','product']);
        if($this->date_from!=''){
            $deals->where('created_at','>=', date('Y-m-d H:i:s',strtotime($this->date_from.' 00:00:00')));
        }if($this->date_to!=''){
            $deals->where('created_at','<=', date('Y-m-d H:i:s',strtotime($this->date_to.' 23:59:59')));
        }

        return view('admin.reports.export.export_monthly_pipeline', [
            'deals' => $deals->get()
        ]);
    }
}
