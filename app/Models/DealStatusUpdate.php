<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DealStatusUpdate extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'deal_status_updates';
    protected $hidden = [

    ];
    protected $fillable = ['deal_id','from_status','status_id','status_name','created_by'];


}
