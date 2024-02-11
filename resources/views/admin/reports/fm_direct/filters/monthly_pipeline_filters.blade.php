<form method="POST" id="monthly_pipeline_form" target="_blank">
    @csrf
    <input name="export_type" id="export_type" type="hidden">
    <div class="row">
        <!--<div class="col-md-6 col-sm-12">-->
        <!--    <div class="form-group">-->
        <!--        <label for="date_type" class="form-label">Dates</label>-->
        <!--        <select class="form-control" name="date_type" id="date_type">-->
        <!--            <option value="financial_year">Financial Year</option>-->
        <!--            <option selected value="current_month">Current Month</option>-->
        <!--            <option value="calendar_year">Calendar Year</option>-->
        <!--        </select>-->
        <!--    </div>-->
        <!--</div>-->
            <div class="col-sm">
                <div class="form-group">
                    <label class="form-label">Start Date</label>
                    <input name="from_date" value="{{ date("01-m-Y") }}" id="from_date" type="text" class=" form-control">
                </div>
            </div>
            <div class="col-sm">
                <div class="form-group">
                    <label class="form-label">End Date</label>
                    <input name="to_date" value="{{ date("t-m-Y") }}" id="to_date" type="text" class=" form-control">
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <button type="button" class="btn btn-info preview">Preview</button>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <button type="button" class="btn btn-secondary export_pdf">Export PDF</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.js" integrity="sha512-RCgrAvvoLpP7KVgTkTctrUdv7C6t7Un3p1iaoPr1++3pybCyCsCZZN7QEHMZTcJTmcJ7jzexTO+eFpHk4OCFAg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function () {
            $("#from_date").datepicker({ format: "dd-mm-yyyy"});
            $('#to_date').datepicker({ format: "dd-mm-yyyy"});
        });
        $(".preview").on("click",function (){
            $("#monthly_pipeline_form").attr("action","{{ route('admin.fm_direct.get_preview_monthly_pipeline_records') }}");
            $("#monthly_pipeline_form").submit();
        });
        $(".export_pdf").on("click",function (){
            $("#monthly_pipeline_form").attr("action","{{ route('admin.fm_direct.export_monthly_pipeline_records') }}");
            $("#monthly_pipeline_form").submit();
        });
    </script>

@endsection
