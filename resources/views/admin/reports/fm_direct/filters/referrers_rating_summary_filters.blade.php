<form method="POST" id="referrer_commission_rating" target="_blank">
    @csrf
    <input name="export_type" id="export_type" type="hidden">
    <div class="row">
        <div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label class="form-label">Start Date</label>
                    <input name="from_date" value="{{ date("01-m-Y") }}" id="from_date" type="text" class=" form-control">
                </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label class="form-label">End Date</label>
                <input name="to_date" value="{{ date("t-m-Y") }}" id="to_date" type="text" class=" form-control">
            </div>
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="form-group mx-2">
                    <button type="button" class="btn btn-primary preview-records">Preview Records</button>
                </div>
                <div class="form-group mx-2">
                    <button type="button" class="btn btn-info preview">Preview PDF</button>
                </div>
                <div class="form-group mx-2">
                    <button type="button" class="btn btn-secondary export_pdf">Export PDF</button>
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
        $("#date_type").on("change",function (){
            if($(this).val()==='financial_year'){
                $("#from_date").datepicker('setDate', '{{ date('01-07-Y',strtotime('-1 Year')) }}');
                $("#to_date").datepicker('setDate', '{{ date('30-06-Y') }}');
            }else if($(this).val()==='current_month'){
                $("#from_date").datepicker('setDate', '{{ date('01-m-Y') }}');
                $("#to_date").datepicker('setDate', '{{ date('t-m-Y') }}');
            }else{
                $("#from_date").datepicker('setDate', '{{ date('01-01-Y') }}');
                $("#to_date").datepicker('setDate', '{{ date('31-12-Y') }}');
            }
        });
        $(".preview-records").on("click",function (){
            $("#referrer_commission_rating").attr("action","{{ route("admin.fm_direct.referrer_commission_rating_preview_records") }}");
            $("#referrer_commission_rating").submit();
        });
        $(".preview").on("click",function (){
            $("#referrer_commission_rating").attr("action","{{ route("admin.fm_direct.referrer_commission_rating_preview") }}");
            $("#referrer_commission_rating").submit();
        });
        $(".export_pdf").on("click",function (){
            $("#referrer_commission_rating").attr("action","{{ route("admin.fm_direct.referrer_commission_rating_export") }}");
            $("#referrer_commission_rating").submit();
        });
    </script>

@endsection
