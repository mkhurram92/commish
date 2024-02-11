<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferrerCommissionModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['referrer_id','commission_model_id'];

    protected $table = 'referrer_commission_model';
}
