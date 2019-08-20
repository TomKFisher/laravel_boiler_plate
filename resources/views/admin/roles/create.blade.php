@extends('layouts.blank')

@push('stylesheets')
@endpush

@section('page_header')
    <!-- partial:partials/page_header.blade.php -->
    @include('includes/page_header', [
        'main_title' => 'Add Role',
        'header_links_left' => [
            [
                'route' => 'roles.index',
                'text' => 'System Roles'
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
                        {!! Form::model($role, ['route'=>'roles.store', 'id'=>'create-form', 'class'=>'card-body', 'novalidate'=>'']) !!}                    
                            <div class="container-fluid px-0 mb-2">
                                <div class="border-bottom mb-3">
                                    <h4>
                                        Role Info
                                    </h4>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('name', 'Role Name', ['class'=>'col-sm-3 col-form-label required', 'disabled' => 'disabled']) !!}
                                    <div class="col-sm-9">
                                        {!! Form::inputs('text', 'name', null, ['class'=>'form-control', 'placeholder' => "Enter a Role Name"]) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('display_name', 'Display Name', ['class'=>'col-sm-3 col-form-label required', 'disabled' => 'disabled']) !!}
                                    <div class="col-sm-9">
                                        {!! Form::inputs('text', 'display_name', null, ['class'=>'form-control', 'placeholder' => "Enter a Display Name" ]) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('description', 'Description', ['class'=>'col-sm-3 col-form-label', 'disabled' => 'disabled']) !!}
                                    <div class="col-sm-9">
                                        {!! Form::inputs('text', 'description', null, ['id'=>'email', 'class'=>'form-control', 'placeholder' => "Enter a Description" ]) !!}
                                    </div>
                                </div>
                                <div class="border-top mt-3 pt-3">
                                    {!! Form::button('Save Role', ['type'=>'submit', 'class'=>'btn btn-success mr-2']) !!}
                                    <a href="{{route('roles.index')}}" class="btn btn-outline-secondary">
                                        Back to All Roles
                                    </a>
                                </div>
                            </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 d-flex align-items-stretch grid-margin">
            <div class="row flex-grow">
                <div class="col-md-12">
                    <div class="card">        
                        <div class="card-body">
                            <div class="border-bottom mb-3">
                                <h4>
                                    Role Permissions
                                </h4>
                            </div>
                            <div class="form-group">
                                <p><strong><i>Please Note:</i></strong> You must save the Role before you can start to add permissions to it.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush