<div class="modal fade broker_staff-edit-modal" id="broker_staff-edit-modal" tabindex="-1" role="dialog" aria-labelledby="Deal Tasks" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Broker Staff</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('admin.brokers.editBrokerStaff',['broker_staff_id'=>encrypt($broker_staff->id)]) }}" onsubmit="return saveAddBrokerStaffForm(this)" id="add_broker_staff_form">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label class="form-label font-weight-bold">Surname</label>
                                <input type="text" name="surname" id="name" class="form-control" maxlength="255" value="{{ $broker_staff?->surname }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label class="form-label font-weight-bold">Given Name</label>
                                <input type="text" name="given_name" id="name" class="form-control" maxlength="255" value="{{ $broker_staff?->given_name }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label class="form-label font-weight-bold">Email</label>
                                <input type="text" name="email" id="name" class="form-control" maxlength="255" value="{{ $broker_staff?->email }}" />
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label class="form-label font-weight-bold">Mobile</label>
                                <input type="text" name="mobile" id="name" class="form-control" maxlength="255" value="{{ $broker_staff?->mobile }}" />
                            </div>
                        </div>
                        <button class="mt-1 btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>

    </div>
</div>

