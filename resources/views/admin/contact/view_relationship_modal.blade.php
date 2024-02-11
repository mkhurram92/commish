<div class="modal fade relationship-view-modal" id="relationship-view-modal" tabindex="-1" role="dialog" aria-labelledby="Deal Tasks" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">View Relationship</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label class="form-label font-weight-bold">Relation with</label>
                            <input type="text" class="form-control" disabled readonly value="{{ $data->relationWith?-> surname . " " . $data->relationWith?->preferred_name }}" />
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="service_id" class="form-label font-weight-bold">Relation</label>
                            <input type="text" class="form-control" disabled readonly value="{{ $data->relationLabel?->name }}" />
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" aria-label="Close">
                    Close
                </button>
            </div>
        </div>

    </div>
</div>
