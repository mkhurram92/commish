<form method="GET" id="referror_invoice" target="_blank">
    @csrf
    <div class="row">
        <div class="col-sm">
            <div class="form-group">
                <label class="form-label">Start Date (DD-MM-YYYY)</label>
                <input name="start_date" value="{{ date('d-m-Y') }}" id="start_date" type="text" class="form-control">
            </div>
        </div>
        <div class="col-sm">
            <div class="form-group">
                <label class="form-label">End Date (DD-MM-YYYY)</label>
                <input name="end_date" value="{{ date('d-m-Y') }}" id="end_date" type="text" class="form-control">
            </div>
        </div>
        <div class="col-sm">
            <div class="form-group">
                <label class="form-label">Referrors</label>
                <select class="form-control" id="referror_list" name="referror_list">
                    <option value=""></option>
                    @foreach ($distinctDisplayNames as $displayName)
                        <option value="{{ $displayName->cs_id }}">{{ $displayName->display_name }}</option>
                    @endforeach
                </select>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.js"
        integrity="sha512-RCgrAvvoLpP7KVgTkTctrUdv7C6t7Un3p1iaoPr1++3pybCyCsCZZN7QEHMZTcJTmcJ7jzexTO+eFpHk4OCFAg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            $("#start_date").datepicker({
                format: "dd-mm-yyyy"
            });
            $('#end_date').datepicker({
                format: "dd-mm-yyyy"
            });
        });
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
        $("#start_date").on('input', function() {
            formatInputDate($(this));
        });

        // Attach the input event to format the date as the user types for 'to_date'
        $("#end_date").on('input', function() {
            formatInputDate($(this));
        });

        $(".preview").on("click", function() {
            //if ($("#referror_list").val() === "") {
            //    alert("Please select an referror before previewing.");
            //    return false;
            //}
            $("#referror_invoice").attr("action", "{{ route('admin.referrors.referror_invoice') }}");
            $("#referror_invoice").submit();
        });
    </script>
@endsection
