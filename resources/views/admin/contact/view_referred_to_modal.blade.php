<div class="modal fade referred_to-view-modal" id="referred_to-view-modal" tabindex="-1" role="dialog" aria-labelledby="Deal Tasks" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">View Referred To</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-row">
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label class="form-label font-weight-bold">Referred To</label>
                            <input type="text" class="form-control" disabled readonly value="{{ $data?->referred_to }}" />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="service_id" class="form-label font-weight-bold">Service</label>
                            <input type="text" class="form-control" disabled readonly value="{{ $data?->service?->name }}" />
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label class="form-label font-weight-bold">Date</label>
                            <input type="text" placeholder="dd/mm/yyyy" id="date" class="form-control" disabled readonly value="{{ $data?->date }}" />
                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="position-relative form-group">
                            <label for="exampleText" class="form-label font-weight-bold">Note</label>
                            <textarea class="form-control" disabled readonly>{{ $data?->notes }}</textarea>
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
