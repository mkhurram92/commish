<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrokerStaff extends Model
{
    use HasFactory;
    protected $table="brokers_staffs";

    protected $fillable = [
        "given_name",
        "surname",
        "email",
        "mobile"
    ];
}
