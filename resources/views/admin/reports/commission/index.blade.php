@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    Commission Outstanding Report
@endsection
@section('page_title_con')
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">
                Reports
            </h4>
        </div>
        <div class="page-rightheader ml-auto d-lg-flex d-none">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}" class="d-flex"><svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z"/><path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3"/></svg><span class="breadcrumb-icon"> Home</span></a></li>
                <li class="breadcrumb-item active" aria-current="page"> Reports
                </li>
            </ol>
        </div>
    </div>
@endsection
@section('body')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="main-card mb-3 card add_form_card">
                <div class="card-header">
                    <h5 class="card-title">Commission Outstanding Report</h5>
                </div>
                <div class="card-body">
                    <div class="col-sm-12">
                        <div class="table-responsive">
                            <table class="table table-bordered data-table">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Deal Id</th>
                                    <th>Commission Type</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($outstandings as $outstanding)
                                        <tr>
                                            <td>{{$loop->iteration}}</td>
                                            <td>{{$outstanding[0]}}</td>
                                            <td>{{$outstanding[1]}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('reports_script')
    @include('layout.datatable')
    <script>
        jQuery(document).ready(function() {
            $('.data-table').DataTable();
        });
    </script>
@endsection
