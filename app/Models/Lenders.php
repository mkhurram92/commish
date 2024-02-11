<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lenders extends Model
{
    protected $table='lenders';
    use HasFactory;
    public function deals(){
        return $this->hasMany(Deal::class,'lender_id');
    }
}
