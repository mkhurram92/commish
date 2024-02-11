<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ClientRelation extends Model
{
    protected $table = 'client_relations';
    use HasFactory;
    protected $dates = ['deleted_at'];

    protected $fillable = [
        "client_id",
        "relation_with",
        "relation",
        "mailout",
    ];

    public function relationLabel()
    {
        return $this->belongsTo(Relationship::class,'relation');
    }
    function relationWith(){
        return $this->belongsTo(ContactSearch::class, "relation_with");
    }
    function withClient(){
        return $this->belongsTo(ContactSearch::class, "client_id");
    }
}
