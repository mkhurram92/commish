<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoanType extends Model
{
	protected $table = 'loan_types';

    use HasFactory;

    protected $hidden = [
         'deleted_at'
    ];
}
?>