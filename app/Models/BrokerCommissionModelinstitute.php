<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrokerCommissionModelinstitute extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['broker_id'];
    protected $table = 'broker_commission_model_institution';
}
