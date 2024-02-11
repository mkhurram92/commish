<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrokerReferror extends Model
{
    protected $table = 'broker_referrors';
    use SoftDeletes;
    protected $dates = ['deleted_at'];
    use HasFactory;
}
