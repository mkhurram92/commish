<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealTask extends Model
{
    protected $table = 'deal_tasks';
    use HasFactory;
    public function deal(){
        return $this->belongsTo(Deal::class);
    }
}
