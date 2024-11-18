<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'broker_id',
        'expense_type_id',
        'amount',
        'additional_details',
    ];

    public function broker()
    {
        return $this->belongsTo(Broker::class, 'broker_id');
    }
    
    public function expenseType()
    {
        return $this->belongsTo(ExpenseType::class);
    }
    
}
