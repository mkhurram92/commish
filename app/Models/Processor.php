<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Processor extends Model
{
    use HasFactory;
    public function deal_tasks(){
        return $this->hasMany(DealTask::class,'processor');
    }
}
