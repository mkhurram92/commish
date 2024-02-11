<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DealNote extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = 'deal_notes';

    protected $fillable = [
        "note",
        "deal_id",
    ];

}
