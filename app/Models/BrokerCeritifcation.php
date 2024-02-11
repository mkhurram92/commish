<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BrokerCeritifcation extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'broker_certifications';
}
