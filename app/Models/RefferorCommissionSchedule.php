<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefferorCommissionSchedule extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected  $table = 'refferor_product_commission_schedule';
    protected $fillable = ['refferor_id','product_id','commission_type_id','flat_rate','per_rate','created_by','note'];
}
