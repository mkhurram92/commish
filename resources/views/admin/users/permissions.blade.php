@extends('layout.main')

@push('style-section')
    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('title')
    User Permissions
@endsection

@section('page_title_con')
    <div class="page-header">
        <div class="page-leftheader">
            <h4 class="page-title">
                {{ $user->fname }} {{ $user->lname }} Permissions
            </h4>
        </div>
        <div class="page-rightheader ml-auto d-lg-flex d-none">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}" class="d-flex">
                        <svg class="svg-icon" xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24"
                            width="24">
                            <path d="M0 0h24v24H0V0z" fill="none" />
                            <path d="M12 3L2 12h3v8h6v-6h2v6h6v-8h3L12 3zm5 15h-2v-6H9v6H7v-7.81l5-4.5 5 4.5V18z" />
                            <path d="M7 10.19V18h2v-6h6v6h2v-7.81l-5-4.5z" opacity=".3" />
                        </svg><span class="breadcrumb-icon"> Home</span></a></li>
                <li class="breadcrumb-item active" aria-current="page"> User Permissions</li>
            </ol>
        </div>
    </div>
@endsection

@section('body')
    <div class="main-card mb-3 card">
        <div class="card-body">
            <div class="col-sm-12">
                <div class="form-group">
                    <form action="{{ route('permissions.store') }}" method="POST">
                        @csrf

                        <!-- Hidden field for user_id -->
                        <input type="hidden" name="user_id" value="{{ $user_id }}">
                        <!-- Multi-select dropdown for brokers -->
                        <!-- Brokers Dropdown -->
                        <div class="form-group">
                            <label for="brokers">Select Brokers:</label>
                            <select name="broker_ids[]" class="form-control select2" multiple required>
                                @foreach ($brokers as $broker)
                                    @if ($broker->is_individual == 1)
                                        <option value="{{ $broker->id }}"
                                            @if (in_array($broker->id, $existingPermissions)) selected @endif>
                                            {{ $broker->surname }} {{ $broker->given_name }}
                                        </option>
                                    @elseif($broker->is_individual == 2)
                                        <option value="{{ $broker->id }}"
                                            @if (in_array($broker->id, $existingPermissions)) selected @endif>
                                            {{ $broker->trading }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Contacts Dropdown -->
                        <div class="form-group">
                            <label for="contacts">Select Contacts:</label>
                            <select name="contact_ids[]" class="form-control select2" multiple required>
                                @foreach ($contacts as $contact)
                                    @if ($contact->individual == 1)
                                        <option value="{{ $contact->id }}"
                                            @if (in_array($contact->id, $existingContactPermissions)) selected @endif>
                                            {{ $contact->surname }} {{ $contact->first_name }}
                                        </option>
                                    @elseif($contact->individual == 2)
                                        <option value="{{ $contact->id }}"
                                            @if (in_array($contact->id, $existingContactPermissions)) selected @endif>
                                            {{ $contact->trading }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Referrors Dropdown -->
                        <div class="form-group">
                            <label for="referrors">Select Referrors:</label>
                            <select name="referror_ids[]" class="form-control select2" multiple required>
                                @foreach ($referrors as $referror)
                                    @if ($referror->individual == 1)
                                        <option
                                            value="{{ $referror->id }}"{{ in_array($referror->id, $existingReferrorPermissions) ? ' selected' : '' }}>
                                            {{ $referror->surname }} {{ $referror->first_name }}
                                        </option>
                                    @elseif($referror->individual == 2)
                                        <option
                                            value="{{ $referror->id }}"{{ in_array($referror->id, $existingReferrorPermissions) ? ' selected' : '' }}>
                                            {{ $referror->trading }}
                                        </option>
                                    @endif
                                @endforeach
                            </select>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group text-right">
                            <button type="submit" class="btn btn-primary mt-2">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('script-section')
    <!-- Select2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select Brokers",
                allowClear: true
            });
        });
    </script>
@endpush
