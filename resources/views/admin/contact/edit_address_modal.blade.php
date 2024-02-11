<div class="modal fade address-edit-modal" id="address-edit-modal" tabindex="-1" role="dialog" aria-labelledby="Deal Tasks" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Edit Addresses</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="{{ route("admin.contact.viewEditAddress",['contact_id'=>encrypt($contact?->id)]) }}" onsubmit="return saveAddressForm(this)" id="edit_address_form">
                    @csrf
                    <div class="form-row">
                        <div class="row">
                            <div class="col-12">
                                <h4>
                                    Address
                                </h4>
                                <div class="row" id="addressRow">
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Unit/Level/Street</label>
                                            <input type="text" placeholder="Unit/Level/Street" name="address[street_number]" id="street_number" value="{{isset($address)?$address?->unit:''}}" class="form-control" maxlength="250" />
                                        </div>
            
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Street
                                                name</label>
                                            <input type="text" placeholder="Street name" name="address[street_name]" id="street_name" value="{{isset($address)?$address?->street_name:''}}" class="form-control" maxlength="250" />
                                        </div>
            
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Street type</label>
                                            <select name="address[street_type]" id="street_type" class="multiselect-dropdown ">
                                                <option value="">Select State</option>
                                                @if(count($streetTypes)>0)
                                                    @foreach($streetTypes as $type)
                                                        <option value="{{$type->id}}" {{isset($address) && $address?->street_type == $type->id ? 'selected="selected"' : ''}}>
                                                            {{$type->name}}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
            
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">City</label>
                                            <input type="text" placeholder="City" name="address[suburb]" id="suburb" class="form-control" value="{{isset($address)?$address?->city:''}}" maxlength="250" />
                                        </div>
            
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">State</label>
                                            <select name="address[state]" id="state" class="multiselect-dropdown ">
                                                <option value="">Select State</option>
                                                @if(count($states)>0)
                                                @foreach($states as $state)
                                                <option value="{{$state->id}}" {{isset($address) && $address?->state == $state->id ? 'selected="selected"' : ''}}>
                                                    {{$state->name}}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @if($errors->has('state'))
                                            <div class="error" style="color:red">{{$errors->first('state')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Postal
                                                Code</label>
                                            <input id="postal_code" name="address[postal_code]" value="{{isset($address)?$address?->postal_code:''}}" placeholder="Postal Code" type="text" class="form-control alpha-num" maxlength="10">
                                            @if($errors->has('postal_code'))
                                            <div class="error" style="color:red">{{$errors->first('postal_code')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="col-12">
                                <div class="row" id="postalAddressRow">
                                    <div class="col-12 d-flex justify-content-between">
                                        <div class="title">
                                            <h4>
                                                Postal Address
                                            </h4>
                                        </div>
                                        <div class="sameAsAddress">
                                            <input type="checkbox" name="same_as_address" class="checkbox" id="sameAsAddress">
                                            <label for="sameAsAddress">Same as Address</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Unit/Level/Street</label>
                                            <input type="text" placeholder="Unit/Level/Street" name="postal_address[street_number]" id="street_number" value="{{isset($postalAddress)?$postalAddress?->unit:''}}" class="form-control" maxlength="250" />
                                        </div>
            
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Street
                                                name</label>
                                            <input type="text" placeholder="Street name" name="postal_address[street_name]" id="street_name" value="{{isset($postalAddress)?$postalAddress?->street_name:''}}" class="form-control" maxlength="250" />
                                        </div>
            
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Street type</label>
                                            <select name="postal_address[street_type]" id="postal_street_type" class="multiselect-dropdown ">
                                                <option value="">Select State</option>
                                                @if(count($streetTypes)>0)
                                                    @foreach($streetTypes as $type)
                                                        <option value="{{$type->id}}" {{isset($postalAddress) && $postalAddress?->street_type == $type->id ? 'selected="selected"' : ''}}>
                                                            {{$type->name}}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </div>
            
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">City</label>
                                            <input type="text" placeholder="City" name="postal_address[suburb]" id="suburb" class="form-control" value="{{isset($postalAddress)?$postalAddress?->city:''}}" maxlength="250" />
                                        </div>
            
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">State</label>
                                            <select name="postal_address[state]" id="postal_address_state" class="multiselect-dropdown ">
                                                <option value="">Select State</option>
                                                @if(count($states)>0)
                                                @foreach($states as $state)
                                                <option value="{{$state->id}}" {{isset($postalAddress) && $postalAddress?->state == $state->id ? 'selected="selected"' : ''}}>
                                                    {{$state->name}}
                                                </option>
                                                @endforeach
                                                @endif
                                            </select>
                                            @if($errors->has('state'))
                                            <div class="error" style="color:red">{{$errors->first('state')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="position-relative form-group">
                                            <label class="form-label font-weight-bold">Postal
                                                Code</label>
                                            <input id="postal_code" name="postal_address[postal_code]" value="{{isset($postalAddress)?$postalAddress?->postal_code:''}}" placeholder="Postal Code" type="text" class="form-control alpha-num" maxlength="10">
                                            @if($errors->has('postal_code'))
                                            <div class="error" style="color:red">{{$errors->first('postal_code')}}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <button class="btn btn-default btn-sm mx-2" type="button">
                                    Close
                                </button>
                                <button class="btn btn-primary mx-2" type="submit">
                                    Submit
                                </button>
                            </div>
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
    jQuery("#state").select2()
    jQuery("#postal_address_state").select2()
    jQuery("#street_type").select2()
    jQuery("#postal_street_type").select2()
    jQuery("#sameAsAddress").on("change",e=>{
        if($(e.target).is(":checked")){
            const street_number = $("#addressRow").find("#street_number").val();
            const street_name = $("#addressRow").find("#street_name").val();
            const street_type = $("#addressRow").find("#street_type").val();
            const suburb = $("#addressRow").find("#suburb").val();
            const state = $("#addressRow").find("#state").val();
            const postal_code = $("#addressRow").find("#postal_code").val();
    
            $("#postalAddressRow #street_number").val(street_number)
            $("#postalAddressRow #street_name").val(street_name)
            $("#postalAddressRow #postal_street_type").val(street_type).trigger("change")
            $("#postalAddressRow #suburb").val(suburb)
            $("#postalAddressRow #postal_address_state").val(state).trigger("change");
            $("#postalAddressRow #postal_code").val(postal_code)
        }
    })
</script>