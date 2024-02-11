<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CommissionData extends Model
{
    protected $table = 'commissions_data';
    protected $fillable = ['loan_writer','line_of_business','commission_type','funder','client','account_number','period','loan_amount','rate','commission','gst','total_paid','referrer','settlement_date','payment_no'];
    use HasFactory;
    use SoftDeletes;
    public function broker(){
        return $this->belongsTo(Broker::class);
    }
    public function broker_staff(){
        return $this->belongsTo(Broker::class,'broker_staff_id','id');
    }
    public function deal(){
        return $this->belongsTo(Deal::class);
    }
}
