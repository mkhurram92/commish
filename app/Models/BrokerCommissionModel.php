<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrokerCommissionModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['broker_id','commission_model_id'];

    protected $table = 'broker_commission_model';
}
