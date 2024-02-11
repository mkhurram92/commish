@extends('layout.main')
@push('style-section')
@endpush
@section('title')
    View Contact::{{$contact->display_name}}

@endsection
@section('page_title_con')
<div class="app-page-title mb-0">
    <div class="page-title-wrapper">
        <div class="page-title-heading">

            <div>
                View Contact::{{$contact->display_name}}
            </div>
        </div>

    </div>
</div>
@endsection
@section('body')
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <div class="main-card mb-3 card">
                <div class="card-body">
                   <div class="col-sm-12">
                       <div class="row">
                           <div class="col-sm-3">
                               <p class="mb-2"><b>Type:</b>{{($contact->search_for == 1 ? 'Client' : 'Referror')}}</p>
                               <p class="mb-2"><b>Individual:</b>{{($contact->individual == 1 ? 'Yes' : 'No')}}</p>
                               <p class="mb-2"><b>Include In General Mailout:</b>{{($contact->general_mail_out == 1 ? 'Yes' : 'No')}}</p>
                               <p class="mb-2"><b>Trading/Business:</b>{{$contact->trading}}</p>
                               <p class="mb-2"><b>Trust Name:</b>{{$contact->trust_name}}</p>
                           </div>
                           <div class="col-sm-3">
                               <p class="mb-2"><b>Salutation:</b>{{$contact->role_title}}</p>
                               <p class="mb-2"><b>Surname:</b>{{$contact->surname}}</p>
                               <p class="mb-2"><b>Given Name:</b>{{$contact->preferred_name}}</p>
                               <p class="mb-2"><b>DOB:</b>{{$contact->dob}}</p>
                               <p class="mb-2"><b>Entity Name:</b>{{$contact->entity_name}}</p>
                               <p class="mb-2"><b>Principle Contact:</b>{{$contact->principle_contact}}</p>
                           </div>
                           <div class="col-sm-3">
                               <p class="mb-2"><b>Work Phone:</b>{{$contact->work_phone}}</p>
                               <p class="mb-2"><b>Home Phone:</b>{{$contact->home_phone}}</p>
                               <p class="mb-2"><b>Mobile Phone:</b>{{$contact->mobile_phone}}</p>
                               <p class="mb-2"><b>Fax:</b>{{$contact->fax}}</p>
                               <p class="mb-2"><b>Email:</b>{{$contact->email}}</p>
                               <p class="mb-2"><b>Web:</b>{{$contact->web}}</p>
                           </div>
                           <div class="col-sm-3">
                               <p class="mb-2"><b>State:</b>{{$contact->state_name}}</p>
                               <p class="mb-2"><b>City:</b>{{$contact->city_name}}</p>
                               <p class="mb-2"><b>Postal Code:</b>{{$contact->postal_code}}</p>
                               <p class="mb-2"><b>ABP:</b>{{$contact->abp_name}}</p>
                               <p class="mb-2"><b>ABN:</b>{{$contact->abn}}</p>
                               <p class="mb-2"><b>Industry:</b>{{$contact->industry_name}}</p>
                           </div>
                       </div>
                   </div>
                </div>
            </div>
        </div>
    </div>
@endsection
