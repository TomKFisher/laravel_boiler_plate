@extends('layouts.blank')

@push('stylesheets')
@endpush

@section('page_header')
    <!-- partial:partials/page_header.blade.php -->
    @include('includes/page_header', [
        'main_title' => 'Add User',
        'header_links_left' => [
            [
                'route' => 'users.index',
                'text' => 'User Management'
            ]
        ]
    ])
    <!-- partial -->
@endsection

@section('main_container')
<div class="row">
        <div class="col-md-6 d-flex align-items-stretch grid-margin">
            <div class="row flex-grow">
                <div class="col-md-12">
                    <div class="card">
                        {!! Form::model($user, ['route'=>'users.store', 'id'=>'create-form', 'class'=>'card-body', 'novalidate'=>'']) !!}
                            <div class="container-fluid px-0 mb-2">
                                <div class="border-bottom mb-3">
                                    <h4>
                                        User Info
                                    </h4>
                                </div>
                                <div class="mb-2">
                                    <p><strong><em>Please Note:</em></strong> Once created the User will be sent an invite to verify their email address and set up their password.</p>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('name', 'User\'s Name', ['class'=>'col-sm-3 col-form-label required', 'disabled' => 'disabled']) !!}
                                    <div class="col-sm-9">
                                        {!! Form::inputs('text', 'name', null, ['class'=>'form-control', 'placeholder' => "User's Name"]) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('email', 'Email', ['class'=>'col-sm-3 col-form-label required', 'disabled' => 'disabled']) !!}
                                    <div class="col-sm-9">
                                        {!! Form::inputs('text', 'email', null, ['id'=>'email', 'class'=>'form-control', 'placeholder' => "Email Address" ]) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('roles', 'Roles', ['class'=>'col-sm-3 col-form-label required', 'disabled' => 'disabled']) !!}
                                    <div class="col-sm-9">
                                        {!! Form::selects('roles[]', $roles, null, ['multiple'=>'multiple', 'class'=>'form-control']) !!}
                                    </div>
                                </div>
                                <div class="border-top mt-3 pt-3">
                                    {!! Form::button('Save User', ['type'=>'submit', 'class'=>'btn btn-success mr-2']) !!}
                                    <a href="{{route('users.index')}}" class="btn btn-outline-secondary">
                                        Back to User Management
                                    </a>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush