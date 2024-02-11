<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DealCommission extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected  $table = 'deal_commissions';
    protected $hidden = [
        'deleted_at','deleted_by','updated_by','created_by'
    ];

    protected $fillable = [

        'deal_id','order','type','total_amount','date_statement','agg_amount','broker_amount','bro_amt_date_paid','referror_amount',
        'ref_amt_date_paid','created_by'
    ];
    public function deal(){
        return $this->belongsTo(Deal::class);
    }


}
