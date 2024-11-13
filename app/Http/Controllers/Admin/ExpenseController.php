<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;

class ExpenseController extends Controller
{
    // Create Expense
    public function create(Request $request)
    {
        $request->validate([
            'broker_id' => 'required|integer',
            'expense_type_id' => 'required|integer',
            'amount' => 'required|numeric',
            'additional_details' => 'required|string|max:100',
        ]);

        $expense = Expense::create($request->all());

        return response()->json($expense, 201);
    }

    // Get all Expenses
    public function index()
    {
        $expenses = Expense::all();
        return view('admin.expenses.index', compact('expenses'));
    }

    // Get a single Expense
    public function show($id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], 404);
        }
        return response()->json($expense);
    }

    // Update an Expense
    public function update(Request $request, $id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        $request->validate([
            'broker_id' => 'required|integer',
            'expense_type_id' => 'required|integer',
            'amount' => 'required|numeric',
            'additional_details' => 'required|string|max:100',
        ]);

        $expense->update($request->all());

        return response()->json($expense);
    }

    // Delete an Expense
    public function destroy($id)
    {
        $expense = Expense::find($id);
        if (!$expense) {
            return response()->json(['message' => 'Expense not found'], 404);
        }

        $expense->delete();

        return response()->json(['message' => 'Expense deleted successfully']);
    }
}
