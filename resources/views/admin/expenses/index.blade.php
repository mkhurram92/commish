@extends('layout.main')

@section('title')
    Expenses
@endsection

@section('body')
    <div class="mb-4">
        <h1>Add Expense</h1>

         <!-- Create Expense Form -->
        <form action="{{ route('admin.expense.store') }}" method="POST">
            @csrf
            <div class="row">
                <!-- Broker Name Instead of ID -->
                <div class="form-group col-md-4">
                    <label for="broker_id">Broker</label>
                    <select name="broker_id" id="broker_id" class="form-control" required>
                        <option value="">Select Broker</option>
                        @foreach ($brokers as $broker)
                            <option value="{{ $broker->id }}">{{ $broker->fullName() }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Expense Type and Amount -->
                <div class="form-group col-md-4">
                    <label for="expense_type_id">Expense Type</label>
                    <select name="expense_type_id" id="expense_type_id" class="form-control" required>
                        <option value="">Select Expense Type</option>
                        @foreach ($expenseTypes as $expenseType)
                            <option value="{{ $expenseType->id }}">{{ $expenseType->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-4">
                    <label for="amount">Amount</label>
                    <input type="number" step="0.01" name="amount" class="form-control" required>
                </div>
            </div>

            <!-- Additional Details -->
            <div class="form-group">
                <label for="additional_details">Additional Details</label>
                <textarea name="additional_details" class="form-control" rows="4" required></textarea>
            </div>

            <!-- Save Button: Shifted to the right -->
            <div class="text-right">
                <button type="submit" class="btn btn-success">Save</button>
            </div>
        </form>
    </div>

    <!-- List of Expenses -->
    <div>
        <h1>Expenses List</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Broker</th>
                    <th>Expense Type</th>
                    <th>Amount</th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $expense)
                    <tr>
                        <td>{{ $expense->id }}</td>
                        <td>{{ optional($expense->broker)->fullName() ?? 'No Broker' }}</td>
                        <!-- Display Expense Type Name -->
                        <td>{{ optional($expense->expenseType)->name ?? 'No Expense Type' }}</td>
                        <td>{{ $expense->amount }}</td>
                        <td>{{ $expense->additional_details }}</td>
                        <td>
                            <a href="#" class="btn btn-warning">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
