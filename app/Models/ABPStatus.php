<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ABPStatus extends Model
{
    protected $table = 'abp_statuses';

    use SoftDeletes;
    use HasFactory;

    protected $hidden = [
         'deleted_at'
    ];

}
