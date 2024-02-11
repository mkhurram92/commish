<?php
namespace App\Exports;

use App\Models\CmBranch;
use App\Models\ContactSearch;
use App\Models\Deal;
use App\Models\Poll;
use App\Models\Service;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ClientExport implements FromView
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
        $clients=ContactSearch::select('id','surname','preferred_name','middle_name','entity_name','general_mail_out',
            'work_phone','home_phone','mobile_phone',
            'email','mail_state','mail_postal_code',
            'street_number','street_name','street_type',
            'suburb'
        )->where('search_for',1)->with('mail_state_table');
        if($this->group_by!=''){
            if($this->group_by=='lender'){
                $clients->groupBy('lender_id');
            }if($this->group_by=='broker_staff'){
                $clients->groupBy('broker_staff_id');
            }
            if($this->group_by=='client'){
                $clients->groupBy('contact_id');
            }

        }
        if($this->date_from!=''){
            $clients->where('created_at','>=', date('Y-m-d H:i:s',strtotime($this->date_from.' 00:00:00')));
        }if($this->date_to!=''){
            $clients->where('created_at','<=', date('Y-m-d H:i:s',strtotime($this->date_to.' 23:59:59')));
        }

        return view('admin.reports.export.export_clients', [
            'clients' => $clients->get()
        ]);
    }
}
