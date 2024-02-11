<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealClient extends Model
{
    protected $table = 'deal_clients_rel';
    use HasFactory;

    protected $fillable = [
        "deal_id",
        "client_id",
        "type",
        "created_by",
        "updated_by",
        "deleted_by",
    ];

    function withDeal()
    {
        return $this->belongsTo(Deal::class, "deal_id");
    }

    function withClient()
    {
        return $this->belongsTo(ContactSearch::class, "client_id");
    }

    function withType()
    {
        return $this->belongsTo(Relationship::class, "type");
    }
}
