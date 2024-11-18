@extends('layout.main')
@push('style-section')
@endpush
@section('title')
        Add Broker
@endsection
@section('page_title_con')
@endsection

@section('content')
    <!-- Create Expense Form -->
    <div class="mb-4">
        <h1>Create New Expense</h1>

        <!-- Display Success Message (if any) -->
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('expenses.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="broker_id">Broker ID</label>
                <input type="number" name="broker_id" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="expense_type_id">Expense Type ID</label>
                <input type="number" name="expense_type_id" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="amount">Amount</label>
                <input type="number" step="0.01" name="amount" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="additional_details">Additional Details</label>
                <input type="text" name="additional_details" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-success">Submit</button>
        </form>
    </div>

    <!-- List of Expenses -->
    <div>
        <h1>Expenses List</h1>

        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Broker ID</th>
                    <th>Expense Type ID</th>
                    <th>Amount</th>
                    <th>Details</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($expenses as $expense)
                    <tr>
                        <td>{{ $expense->id }}</td>
                        <td>{{ $expense->broker_id }}</td>
                        <td>{{ $expense->expense_type_id }}</td>
                        <td>{{ $expense->amount }}</td>
                        <td>{{ $expense->additional_details }}</td>
                        <td>
                            <a href="{{ route('expenses.edit', $expense->id) }}" class="btn btn-warning">Edit</a>
                            <form action="{{ route('expenses.destroy', $expense->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
