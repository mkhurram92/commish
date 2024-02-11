<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactAddress extends Model
{
    use HasFactory;
    protected $table = "contact_address";
    public $timestamps = false;
    protected $fillable = [
        "unit",
        "street_name",
        "street_type",
        "city",
        "state",
        "postal_code",
        "contact_id",
        "address_type",
    ];

    public function withContact(){
        return $this->belongsTo(ContactSearch::class, "contact_id");
    }
    
    public function withState(){
        return $this->belongsTo(State::class, "state");
    }
    public function withStreetType(){
        return $this->belongsTo(StreetType::class, "street_type");
    }
}
