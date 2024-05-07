<form method="POST" id="monthly_pipeline_form" target="_blank">
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
        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label class="form-label">Start Date (DD-MM-YYYY)</label>
                <input name="from_date" value="{{ date('01-m-Y') }}" id="from_date" type="text"
                    class=" form-control">
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="form-group">
                <label class="form-label">End Date (DD-MM-YYYY)</label>
                <input name="to_date" value="{{ date('t-m-Y') }}" id="to_date" type="text" class=" form-control">
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.js"
        integrity="sha512-RCgrAvvoLpP7KVgTkTctrUdv7C6t7Un3p1iaoPr1++3pybCyCsCZZN7QEHMZTcJTmcJ7jzexTO+eFpHk4OCFAg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            $("#from_date").datepicker({
                format: "dd-mm-yyyy"
            });
            $('#to_date').datepicker({
                format: "dd-mm-yyyy"
            });
        });
        $(".preview").on("click", function() {
            $("#monthly_pipeline_form").attr("action",
                "{{ route('admin.fm_direct.get_preview_monthly_pipeline_records') }}");
            $("#monthly_pipeline_form").submit();
        });
        $(".export_pdf").on("click", function() {
            $("#monthly_pipeline_form").attr("action",
                "{{ route('admin.fm_direct.export_monthly_pipeline_records') }}");
            $("#monthly_pipeline_form").submit();
        });
    </script>
@endsection
