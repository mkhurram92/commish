<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Broker;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\ExpenseType;

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

        //return response()->json($expense, 201);
        return redirect()->route('admin.expense.index')->with('success', 'Expense created successfully!');
    }

    // Get all Expenses
    public function index()
    {
        $expenses = Expense::with('broker', 'expenseType')->get();
        $brokers = Broker::all();
        $expenseTypes = ExpenseType::all();

        return view('admin.expenses.index', compact('expenses', 'expenseTypes', 'brokers'));
    }
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validated = $request->validate([
            'broker_id' => 'required|exists:brokers,id',
            'expense_type_id' => 'required|exists:expense_types,id',
            'amount' => 'required|numeric|min:0',
            'additional_details' => 'nullable|string|max:255',
        ]);

        // Create the new expense record
        $expense = new Expense();
        $expense->broker_id = $validated['broker_id'];
        $expense->expense_type_id = $validated['expense_type_id'];
        $expense->amount = $validated['amount'];
        $expense->additional_details = $validated['additional_details'] ?? null;  // If no additional details, set as null
        $expense->save(); // Save to the database

        // Redirect back with a success message
        return redirect()->route('admin.expense.index')->with('success', 'Expense created successfully!');
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
