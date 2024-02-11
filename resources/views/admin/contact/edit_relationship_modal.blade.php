<div class="modal fade relationship-edit-modal" id="relationship-edit-modal" tabindex="-1" role="dialog" aria-labelledby="Deal Tasks" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Relationship</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route('admin.contact.addrelationship',['contact_id'=>encrypt($data?->client_id)]) }}" onsubmit="return saveRelationshipForm(this)" id="add_referred_to_form">
                    @csrf
                    <div class="form-row">
                        <input type="hidden" name="edit_id" id="edit_id" value="{{ encrypt($data?->id) }}" />
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label class="form-label font-weight-bold">Relationship with</label>
                                <select name="relation_with" id="relation_with" class="multiselect-dropdown form-control">
                                    <option value="">Select Client</option>
                                    @if(count($clients)>0)
                                        @foreach($clients as $client)
                                            <option value="{{$client->id}}" @if($data?->relation_with==$client->id) selected @endif>
                                                {{$client->surname}} {{$client->preferred_name}}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="relation" class="form-label font-weight-bold">Relation</label>
                                <select name="relation" id="relation" class="multiselect-dropdown form-control">
                                    <option value="">Select Relation</option>
                                        @if(count($relations)>0)
                                            @foreach($relations as $relation)
                                                <option value="{{$relation->id}}" @if($data?->relation==$relation->id) selected @endif>
                                                    {{$relation->name}}
                                                </option>
                                            @endforeach
                                        @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="mt-1 btn btn-primary">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
            </div>
        </div>
    </div>
</div>
<script>
    jQuery("#relationship-edit-modal").find("#relation").select2();
    jQuery("#relationship-edit-modal").find("#relation_with").select2();
</script>
