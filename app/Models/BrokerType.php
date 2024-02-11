<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrokerType extends Model
{
    protected $table = 'broker_types';
    use SoftDeletes;
    use HasFactory;
}
