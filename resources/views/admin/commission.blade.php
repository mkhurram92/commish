@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    Commission Model
@endsection
@section('page_title_con')

    @endsection
@section('body')
    <div class="main-card mb-3 card add_form_card">
        <div class="card-body">
            <h5 class="card-title">Add New</h5>
            <div>
                <form class="form-inline" id="addForm" method="post" action="">
                    <div class="col-sm-4">
                        <div class="mb-2 mr-sm-2 mb-sm-0 position-relative form-group">
                            <label for="name" class="mr-sm-2">Name</label>
                            <input name="name" id="name" placeholder="" type="text" class="form-control">
                        </div>
                    </div>
                    <button class="btn btn-primary" type="submit">Submit</button>
                </form>
                <div class="divider"></div>

            </div>
        </div>
    </div>
@endsection

@push('script-section')
    <script>
        $(document).ready(() => {
            $("#addForm").validate({
                rules: {

                    name: {
                        required: true,
                        minlength: 2,
                    }
                },
                messages: {
                    name: {
                        required: "Please enter a name",
                        minlength: "Your name must consist of at least 2 characters",
                    }
                },
                errorElement: "em",
                errorPlacement: function (error, element) {
                    // Add the `invalid-feedback` class to the error element
                    error.addClass("invalid-feedback");
                    if (element.prop("type") === "checkbox") {
                        error.insertAfter(element.next("label"));
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass("is-invalid").removeClass("is-valid");
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).addClass("is-valid").removeClass("is-invalid");
                },
                submitHandler: function(){

                    $.ajax({
                        url: "{{ route('admin.commission.post') }}",
                        type:'POST',
                        data:  {
                        "_token": "{{ csrf_token() }}",
                            "name": jQuery('#name').val()
                             },
                        success: function(data) {

                            if($.isEmptyObject(data.error)){
                                successMessage(data.success);
                            }else{
                                printErrorMsg(data.error);
                            }
                        },error:function(jqXHR, textStatus, errorThrown)
                        {
                            errorMessage(jqXHR.responseText)

                        }
                    });
                    return false;
                }
            });
        });
    </script>
@endpush
