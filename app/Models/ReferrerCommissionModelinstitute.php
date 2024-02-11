<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReferrerCommissionModelinstitute extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['referrer_id'];
    protected $table = 'referrer_commission_model_institution';
}
