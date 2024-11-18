@extends('layout.main')

@section('title')
    Expenses
@endsection
@section('page_title_con')
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">
                Expenses
            </h4>
        </div>
        <div class="page-rightheader ml-auto d-lg-flex d-none">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="d-flex"><svg class="svg-icon"
                            xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z" />
                            <path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3" />
                        </svg><span class="breadcrumb-icon"> Home</span></a></li>

                <li class="breadcrumb-item active" aria-current="page"> Expenses
                </li>
            </ol>
        </div>
    </div>
@endsection

@section('body')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="main-card mb-3 card add_form_card">
                <div class="card-body">
                    <h5 class="card-title">Add New</h5>
                    <div>
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
                </div>
            </div>
        </div>
    </div>
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="col-sm-12">
                <div class="">
                    <table style="width: 100%" id="TableData"
                        class="table table-hover table-striped table-bordered yajra-datatable">
                        <thead>
                            <tr>
                                <th class="noVis">Broker</th>
                                <th class="noVis">Expense Type</th>
                                <th class="noVis">Amount</th>
                                <th class="noVis">Details</th>
                                <th class="noVis">Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expenses as $expense)
                                <tr>
                                    <td>{{ optional($expense->broker)->fullName() ?? 'No Broker' }}</td>
                                    <!-- Display Expense Type Name -->
                                    <td>{{ optional($expense->expenseType)->name ?? 'No Expense Type' }}</td>
                                    <td>{{ $expense->amount }}</td>
                                    <td>{{ $expense->additional_details }}</td>
                                    <td>{{ \Carbon\Carbon::parse($expense->created_at)->format('Y-m-d') }}</td>
                                    <td>
                                        <a href="#" class="btn btn-warning">Edit</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th class="noVis">Broker</th>
                                <th class="noVis">Expense Type</th>
                                <th class="noVis">Amount</th>
                                <th class="noVis">Details</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
