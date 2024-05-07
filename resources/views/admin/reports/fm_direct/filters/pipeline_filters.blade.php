<form method="POST" id="pipeline_form" target="_blank">
    @csrf
    <input name="export_type" id="export_type" type="hidden">
    <div class="row">
        <div class="col-md-3 col-sm-12">
            <div class="form-group">
                <?php
                $brokers = \App\Models\Broker::where('is_active', 1)->get();
                ?>
                <label for="broker_id" class="form-label">Broker Name</label>
                <select class="form-control" name="broker_id" id="broker_id">
                    <option value=""></option>
                    @php
                        $sortedBrokers = $brokers->sortBy(function ($broker) {
                            return $broker->is_individual == 1 ? $broker->surname : $broker->trading;
                        });
                    @endphp

                    @foreach ($sortedBrokers as $broker)
                        @if ($broker->is_individual == 1)
                            <option value="{{ $broker->id }}">{{ $broker->surname }} {{ $broker->given_name }}
                            </option>
                        @elseif($broker->is_individual == 2)
                            <option value="{{ $broker->id }}">{{ $broker->trading }}</option>
                        @endif
                    @endforeach
                </select>
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <div class="form-group">
                <label class="form-label">Start Date (DD-MM-YYYY)</label>
                <input name="from_date" value="{{ date('d-m-Y') }}" id="from_date" type="text" class=" form-control">
            </div>
        </div>
        <div class="col-md-3 col-sm-12">
            <div class="form-group">
                <label class="form-label">End Date (DD-MM-YYYY)</label>
                <input name="to_date" value="{{ date('d-m-Y') }}" id="to_date" type="text" class=" form-control">
            </div>
        </div>

        <div class="col-md-3 col-sm-12">
            <div class="form-group">
                <label for="group_by" class="form-label">Group By</label>
                <select class="form-control" name="group_by" id="group_by">
                    <option value="BrokerStaff">BrokerStaff</option>
                    <option value="Lender">Lender</option>
                    <option value="Product">Product</option>
                    <option value="Status">Status</option>
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
           $("#pipeline_form").attr("action","{{ route('admin.fm_direct.preview_pipeline') }}");
            $("#pipeline_form").submit();
        });
        $(".export_pdf").on("click",function (){
           $("#pipeline_form").attr("action","{{ route('admin.fm_direct.export_pipeline_records') }}");
            $("#pipeline_form").submit();
        });
    </script>



<script>
    $(document).ready(function () {
        $(document).ready(function () {
            $("#from_date").datepicker({ format: "dd-mm-yyyy"});
            $('#to_date').datepicker({ format: "dd-mm-yyyy"});
        });
        // Function to format the date as the user types
        function formatInputDate(input) {
            var val = input.val().replace(/\D/g, ''); // Remove non-numeric characters

            if (val.length > 2) {
                val = val.substring(0, 2) + '-' + val.substring(2);
            }

            if (val.length > 5) {
                val = val.substring(0, 5) + '-' + val.substring(5);
            }

            if (val.length > 10) {
                val = val.substring(0, 10); // Limit the total length to 10 characters
            }

            input.val(val);
        }

        // Attach the input event to format the date as the user types for 'from_date'
        $("#from_date").on('input', function () {
            formatInputDate($(this));
        });

        // Attach the input event to format the date as the user types for 'to_date'
        $("#to_date").on('input', function () {
            formatInputDate($(this));
        });
    });
</script>

    
@endsection
