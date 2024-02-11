<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class DealMissing extends Model
{

    protected $table = 'deal_missing';
    protected $fillable = ['loan_ref','funder','client','status','status_date'];
    
    use HasFactory;
    use SoftDeletes;
    protected $hidden = [
        'deleted_at','deleted_by','updated_by','created_by'
    ];

    public function deal_status(){
        return $this->belongsTo(DealsWithMissingRefsStatus::class,'status','status_code');
    }
   
}
