<form method="POST" id="deals_history" target="_blank">
    @csrf
    <input name="export_type" id="export_type" type="hidden">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="status" class="form-label">Status</label>
                <?php $dealStatus = \App\Models\DealStatus::all(); ?>
                <select class="form-control" name="status" id="status">
                    <option>All Status</option>
                    @foreach($dealStatus as $status)
                        <option value="{{$status->id}}">{{$status->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="group_by" class="form-label">Products</label>
                <?php $products = \App\Models\Products::all(); ?>
                <select class="form-control" name="product_id" id="product_id">
                    <option>All Products</option>
                    @foreach($products as $product)
                        <option value="{{$product->id}}">{{$product->name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
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
        $(".preview").on("click",function (){
            $("#deals_history").attr("action","{{ route("admin.fm_direct.get_deals_history") }}");
            $("#deals_history").submit();
        });
        $(".export_pdf").on("click",function (){
            $("#deals_history").attr("action","{{ route("admin.fm_direct.export_deals_history") }}");
            $("#deals_history").submit();
        });
    </script>

@endsection
