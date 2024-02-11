<form method="POST" id="broker_form" target="_blank">
    @csrf
    <input name="export_type" id="export_type" type="hidden">
    <div class="row">
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label for="broker_type" class="form-label">Type</label>
                <select class="form-control" name="broker_type" id="broker_type">
                    <?php
                    $broker_types = \App\Models\BrokerType::whereStatus(1)->whereId(1)->get();
                    ?>
                    @foreach($broker_types as $broker_type)
                        <option value="{{ $broker_type->id }}">{{ $broker_type->name }}</option>
                    @endforeach
                </select>
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

        $(".preview").on("click",function (){
            $("#broker_form").attr("action","{{ route("admin.broker.get_broker_records") }}");
            $("#broker_form").submit();
        });
        $(".export_pdf").on("click",function (){
            $("#broker_form").attr("action","{{ route("admin.broker.export_broker_records") }}");
            $("#broker_form").submit();
        });
    </script>

@endsection
