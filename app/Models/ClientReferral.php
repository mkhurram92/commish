<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ClientReferral extends Model
{

    use HasFactory;

    protected $fillable = [
        "client_id",
        "referred_to",
        "service_id",
        "date",
        "notes",
    ];
    public function service(){
        return $this->belongsTo(Service::class, "service_id");
    }
    public function Contact(){
        return $this->belongsTo(ContactSearch::class, "client_id");
    }

}
