<div class="modal fade relation-edit-modal" id="relation-edit-modal" tabindex="-1" role="dialog" aria-labelledby="Deal Tasks" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">
                    {{ isset($relationship)?"Edit":"Add" }} Relation
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="post" action="@if(isset($relationship))
                        {{ route("admin.deals.editRelation",['relationship'=>encrypt($relationship->id)]) }}
                    @else
                        {{ route("admin.deals.addRelation",['deal_id'=>encrypt($deal?->id)]) }}
                    @endif" onsubmit="return saveRelationForm(this)" id="edit_relation_form">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label class="form-label font-weight-bold">Linked To</label>
                                    <select name="client_id" id="client_id" class="multiselect-dropdown">
                                        <option value="">Select Client</option>
                                            @if (isset($client) && !empty($client))
                                            <option value="{{$client->id}}" {{isset($relationship) && $relationship?->client_id == $client->id ? 'selected="selected"' : ''}}>
                                                {{ $client?->fullname }}
                                            </option>
                                            @endif
                                    </select>
                                </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label class="form-label font-weight-bold">Relation</label>
                                <select name="type" id="type" class="multiselect-dropdown ">
                                    <option value="">Select Relation</option>
                                    @if(count($relations)>0)
                                        @foreach($relations as $relation)
                                            <option value="{{$relation->id}}" {{isset($relationship) && $relationship?->type == $relation->id ? 'selected="selected"' : ''}}>
                                                {{$relation->name}}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                                @if($errors->has('type'))
                                <div class="error" style="color:red">{{$errors->first('type')}}</div>
                                @endif
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-default mx-2" type="button">
                                Close
                            </button>
                            <button class="btn btn-primary mx-2" type="submit">
                                Submit
                            </button>
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
    jQuery("#client_id").select2()
    jQuery("#type").select2()

    initClientSelect($("#client_id"));

    function initClientSelect(element) {
            $(element).select2({
                ajax: {
                    url: "{{ route('deals.getClients') }}",
                    type: "post",
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            _token: $("meta[name='csrf-token']").attr('content'),
                            search: params.term,
                            page: params.page
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
        
                        return {
                            results: data.results,
                            pagination: {
                                more: (params.page * 10) < data.count_filtered
                            }
                        };
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        console.error("AJAX Error: " + textStatus, errorThrown);
                    }
                },
                minimumInputLength: 3,
                autoWidth: true,
                placeholder: "Select Client",
            });
        }
</script>
