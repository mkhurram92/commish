<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class RefferorRelation extends Model
{
    protected $table = 'refferor_relations';

    use SoftDeletes;
    use HasFactory;

    protected $hidden = [
         'deleted_at'
    ];

}
