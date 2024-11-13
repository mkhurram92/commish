@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    Expense List
@endsection

@section('page_title_con')
    <!--Page header-->
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">Expense List</h4>
        </div>
        <div class="page-rightheader d-lg-flex d-none ml-auto">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="d-flex"><svg class="svg-icon"
                            xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z" />
                            <path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3" />
                        </svg><span class="breadcrumb-icon"> Home</span></a></li>

                <li class="breadcrumb-item active" aria-current="page">Expenses</li>
            </ol>
        </div>
    </div>
    <!--End Page header-->
@endsection
@section('body')
    <div class="row">
        <div class="col-md-12">
            <div class="main-card card mb-3">
                <div class="card-body">
                    <form method="get">
                        <div class="row mb-1">
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Broker</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="">Type</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">Expense Type</label>
                                    <input name="expense_type" value="" id="expense_type" type="text"
                                        placeholder="Expense Type" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4 col-sm-12">
                                <div class="form-group">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" onclick="return refreshTable()" class="btn btn-primary mt-1"><i
                                            class="fa fa-search"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="main-card card mb-3">
                <div class="card-body">
                    <div class="table-responsive">

                        <table style="width: 100%;max-width:none !important" id="TableData"
                            class="table-hover table-striped table-bordered display nowrap" data-toggle="table"
                            data-height="500" data-show-columns="true">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Trading/Business</th>
                                    <th>Surname</th>
                                    <th>Given Name</th>
                                    <th>DOB</th>
                                    <th>Mobile Phone</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>

                            </tbody>
                            <tfoot>
                                <tr>
                                    <th>#</th>
                                    <th>Trading/Business</th>
                                    <th>Surname</th>
                                    <th>Given Name</th>
                                    <th>DOB</th>
                                    <th>Mobile Phone</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection