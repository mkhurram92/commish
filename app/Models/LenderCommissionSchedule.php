<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LenderCommissionSchedule extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected  $table = 'lender_product_commission_schedule';
    protected $fillable = ['lender_id','product_id','commission_type_id','flat_rate','per_rate','created_by','note'];
}
