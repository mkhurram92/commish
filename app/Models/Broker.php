<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Broker extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $hidden = [
        'deleted_at','deleted_by','last_updated_by','created_by'
    ];

    public function referrors()
    {
        return $this->hasMany('App\Models\BrokerReferror','broker_id');
    }
    public function referrorClients()
    {
        return $this->hasMany('App\Models\BrokerReferror','broker_id')->leftJoin('contact_searches','contact_searches.id','=','referror')->select(DB::raw('broker_referrors.*,CONCAT_WS(",",contact_searches.trading,contact_searches.trust_name,CONCAT(contact_searches.surname," ",contact_searches.first_name," ",contact_searches.middle_name),contact_searches.preferred_name,contact_searches.entity_name) as referrors_name'));
    }
    public function parent_broker(){
        return $this->belongsTo(Broker::class,'parent_broker','id');
    }
    public function broker_staff(){
        return $this->hasMany(BrokerStaff::class,'broker_id','id')->latest();
    }

    public function commission_data_by_staff(){
        return $this->hasMany(CommissionData::class,'broker_staff_id');
    }
    public function deals(){
        return $this->hasMany(Deal::class);
    }
    public function city_info(){
        return $this->belongsTo(City::class,'city','id');
    }
    public function state_info(){
        return $this->belongsTo(State::class,'state','id');
    }
    public function userBrokers()
    {
        return $this->belongsToMany(User::class, 'user_brokers', 'broker_id', 'user_id');
    }
    public function expenses()
    {
        return $this->hasMany(Expense::class, 'broker_id');
    }
    public function fullName()
    {
        if ($this->is_individual == 1) {
            // If individual, return surname + given_name
            return $this->surname . ' ' . $this->given_name;
        } elseif ($this->is_individual == 2) {
            // If not an individual, return trading name
            return $this->trading;
        }

        return 'No Name Available';
    }

}
