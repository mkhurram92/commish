<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DealsWithMissingRefsStatus extends Model
{
    use HasFactory;

    public static $NEW=0;
    public static $IN_PROGRESS=1;
    public static $COMPLETED=2;

}
