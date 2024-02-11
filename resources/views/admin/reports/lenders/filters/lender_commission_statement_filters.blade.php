<form method="POST" id="lender_form" target="_blank">
    @csrf
    <input name="export_type" id="export_type" type="hidden">
    <div class="row">
        <!--<div class="col-md-6 col-sm-12">
                <div class="form-group">
                    <label class="form-label">Start Date</label>
                    <input name="from_date" value="{{ date('d-m-Y') }}" id="from_date" type="text" class="form-control">
                </div>
            </div>-->
        <div class="col-md-6 col-sm-12">
            <div class="form-group">
                <label class="form-label">Statements Date (DD-MM-YYYY)</label>
                <input name="to_date" value="{{ date('d-m-Y') }}" id="to_date" type="text" class="form-control">
            </div>
        </div>
        <div class="col-sm">
            <div class="form-group">
                <label for="date_type" class="form-label">Institution</label>
                <select class="form-control" name="lenders" id="lenders" required>
                    <option value=""></option>
                    <?php
                    $lenders = \App\Models\Lenders::get();
                    ?>
                    @foreach ($lenders as $lender)
                        <option value="{{ $lender->id }}">{{ $lender->name }}</option>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/datepicker/1.0.10/datepicker.min.js"
        integrity="sha512-RCgrAvvoLpP7KVgTkTctrUdv7C6t7Un3p1iaoPr1++3pybCyCsCZZN7QEHMZTcJTmcJ7jzexTO+eFpHk4OCFAg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        $(document).ready(function() {
            $('#to_date').datepicker({
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
        // Attach the input event to format the date as the user types for 'to_date'
        $("#to_date").on('input', function() {
            formatInputDate($(this));
        });
        $(".preview").on("click", function() {
            if ($("#lenders").val() === "") {
                alert("Please select an Institution before previewing.");
                return false;
            }
            $("#lender_form").attr("action", "{{ route('admin.lender.lender_commission_statement') }}");
            $("#lender_form").submit();
        });
        //$(".export_pdf").on("click", function() {
        //    $("#lender_form").attr("action", "{{ route('admin.lender.export_lender_reconciliation_records') }}");
        //    $("#lender_form").submit();
        //});
    </script>
@endsection
