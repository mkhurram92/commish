<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Referror extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $hidden = [
        'deleted_at','deleted_by','last_updated_by','created_by'
    ];
    
    public function referrer()
    {
        return $this->belongsTo(ContactSearch::class);
    }
}