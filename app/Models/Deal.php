<?php

namespace App\Models;

use App\Console\Commands\Deals;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Deal extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $hidden = [
        'deleted_at',
        'deleted_by',
        'updated_by',
        'created_by'
    ];

    public function getStatusDateAttribute($value)
    {

        return  Carbon::parse($value)->format('d-m-Y');
    }
    public function relations()
    {
        return $this->hasMany('App\Models\DealClient', 'deal_id');
    }
    public function relationDetails()
    {
        return $this->belongsToMany('App\Models\ContactSearch', 'deal_clients_rel', 'deal_id', 'client_id')->join('relationship as client_relationships', 'deal_clients_rel.type', '=', 'client_relationships.id')->select(DB::raw('contact_searches.id, contact_searches.trading,contact_searches.trust_name,contact_searches.surname,contact_searches.individual,contact_searches.preferred_name,contact_searches.middle_name,contact_searches.role_title,contact_searches.entity_name,client_relationships.name as client_relation_label,deal_clients_rel.id as deal_clients_rel_id'));
    }
    public function broker()
    {
        return $this->belongsTo(Broker::class, "broker_id");
    }
    public function referrerDeals($date_from = null, $date_to = null, $referror_split_referror, $broker_id = null)
    {
        $deals = Deal::select("*")
            ->where(function ($query) {
                $query->whereNotNull('referror_split_referror')
                    ->where('broker_est_upfront', '>', "0")
                    ->where('status', 4);
            })
            ->where(function ($q) {
                $q->whereNotNull('referror_split_referror')
                    ->where('broker_est_upfront', '>', "0")
                    ->where('status', 4);
            })
            ->where("referror_split_referror", $referror_split_referror);
        if (!empty($date_from)) {
            $deals->where('status_date', '>=', date('Y-m-d H:i:s', strtotime($date_from)));
        }
        if (!empty($date_to)) {
            $deals->where('status_date', '<=', date('Y-m-d H:i:s', strtotime($date_to)));
        }
        if (!empty($broker_id)) {
            $deals->where('broker_id', $broker_id);
        }
        $deals = $deals->get();
        return $deals;
    }

    public function deal_commission()
    {
        return $this->hasMany(DealCommission::class, 'deal_id', 'id');
    }
    public function deal_commission_trial()
    {
        return $this->hasMany(DealCommission::class, 'deal_id', 'id');
    }
    public function commission_data()
    {
        return $this->hasMany(CommissionData::class);
    }
    public function tasks()
    {
        return $this->hasMany('App\Models\DealTask', 'deal_id');
    }
    public function broker_staff()
    {
        return $this->belongsTo(BrokerStaff::class, 'broker_staff_id');
    }
    public function withLoanType()
    {
        return $this->belongsTo(DealLoanTypes::class, 'loan_type_id');
    }
    public function deal_clients()
    {
        return $this->belongsToMany('App\Models\ContactSearch', 'deal_clients_rel', 'deal_id', 'client_id')->join('deal_client_types as client_relationships', 'deal_clients_rel.type', '=', 'client_relationships.id')->select(DB::raw('contact_searches.trading,contact_searches.trust_name,contact_searches.surname,contact_searches.first_name,contact_searches.preferred_name,contact_searches.middle_name,contact_searches.role_title,contact_searches.entity_name,client_relationships.name as client_relation_label'));
    }
    public function lender()
    {
        return $this->belongsTo(Lenders::class, 'lender_id');
    }
    public function deal_status()
    {
        return $this->belongsTo(DealStatus::class, 'status', 'id');
    }
    public function deal_status_update()
    {
        return $this->hasOne(DealStatusUpdate::class, 'id', 'deal_id');
    }
    public function deal_status_history()
    {
        return $this->belongsToMany(DealStatus::class, 'deal_status_updates', 'deal_id', 'status_id')->withPivot('created_at');
    }

    public function product()
    {
        return $this->belongsTo(Products::class, 'product_id');
    }
    public function client()
    {
        return $this->belongsTo(ContactSearch::class, 'contact_id');
    }
    public function referrer()
    {
        return $this->belongsTo(ContactSearch::class, 'referror_split_referror');
    }

    public function scopeGetAIPExportRecords($q, $group_by = '', $date_from = '', $date_to = '')
    {
        $q->select('deals.*')->with(['lender', 'deal_status', 'client', 'product'])
            ->whereIn('status', [11])->orderBy('created_at');
        if ($group_by != '') {
            if ($group_by == 'lender') {
                $q->groupBy('lender_id');
            }
            if ($group_by == 'broker_staff') {
                $q->groupBy('broker_staff_id');
            }
            if ($group_by == 'client') {
                $q->groupBy('contact_id');
            }
        }
        if ($date_from != '') {
            $q->where('created_at', '>=', date('Y-m-d H:i:s', strtotime($date_from . ' 00:00:00')));
        }
        if ($this->date_to != '') {
            $q->where('created_at', '<=', date('Y-m-d H:i:s', strtotime($date_to . ' 23:59:59')));
        }
        return $q;
    }

    function withNotes()
    {
        return $this->hasMany(DealNote::class, "deal_id")->latest();
    }
    function withClientRel()
    {
        return $this->hasMany(DealClient::class, "deal_id")->latest();
    }
}
