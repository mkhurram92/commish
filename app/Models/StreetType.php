<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class StreetType extends Model
{
    use HasFactory;
    protected $table = 'contact_street_types';

    protected $fillable = [
        "name",
    ];

}
