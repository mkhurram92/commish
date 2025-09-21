<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealStatusUpdate extends Model
{
    use HasFactory;
    protected $table = 'deal_status_updates';
     public $timestamps = false;
    protected $hidden = [

    ];
    protected $fillable = ['deal_id','from_status','status_id','status_name','created_by','created_at'];


}
