<div class="modal fade referred_to-edit-modal" id="referred_to-edit-modal" tabindex="-1" role="dialog" aria-labelledby="Deal Tasks" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Referred To</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('admin.contact.addreferredto',['contact_id'=>encrypt($data?->client_id)]) }}" onsubmit="return saveReferredToForm(this)" id="add_referred_to_form">
                    @csrf
                    <div class="form-row">
                        <input type="hidden" name="edit_id" id="edit_id" value="{{ encrypt($data?->id) }}" />
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label class="form-label font-weight-bold">Referred To</label>
                                <input type="text" name="client_referral" id="client_referrals" class="form-control" maxlength="255" value="{{ $data?->referred_to }}" />
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="service_id" class="form-label font-weight-bold">Service</label>
                                <select name="service_id" id="service_id" class="multiselect-dropdown form-control">
                                    <option value="">Select Service</option>
                                        @if(count($services)>0)
                                            @foreach($services as $service)
                                                <option value="{{$service->id}}" @if($data?->service_id==$service->id) selected @endif>
                                                    {{$service->name}}
                                                </option>
                                            @endforeach
                                        @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label class="form-label font-weight-bold">Date</label>
                                <input type="text" data-toggle="datepicker" placeholder="dd/mm/yyyy" name="date" autocomplete="off" id="date" class="form-control" maxlength="255" value="{{ $data?->date }}" />
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="position-relative form-group">
                                <label for="exampleText" class="form-label font-weight-bold">Note</label>
                                <textarea name="referred_to_note" id="referred_to_note" class="form-control">{{ $data?->notes }}</textarea>
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
