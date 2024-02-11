<form method="POST" id="export_form" >
    @csrf
    <input name="export_type" id="export_type" type="hidden">
    <div class="row">
        <div class="col-md-3">
            <label class="form-label">Start Date</label>
            <input name="from_date" value="" id="from_date" type="date" class=" form-control">
        </div>
        <div class="col-md-3">
            <label class="form-label">End Date</label>
            <input name="to_date" value="" id="to_date" type="date" class=" form-control">
        </div>
    </div>
    <div class="row mt-5">
        <div class="col-md-12 col-sm-12">
            <div class="row">
                <div class="col-sm-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-info filter export pdf pull-right">Preview</button>
                    </div>
                </div>
                <div class="col-sm-2">
                    <div class="form-group">
                        <button type="submit" class="btn btn-secondary filter export excel pull-right">Export PDF</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
