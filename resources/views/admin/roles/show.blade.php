@extends('layouts.blank')

@push('stylesheets')
@endpush

@section('page_header')
    <!-- partial:partials/page_header.blade.php -->
    @include('includes/page_header', [
        'main_title' => 'View Role',
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
                        {!! Form::model($role, ['route'=>'roles.store', 'id'=>'show-account-form', 'class'=>'card-body', 'novalidate'=>'']) !!}
                            <div class="container-fluid px-0 mb-2">
                                <div class="border-bottom mb-3">
                                    <h4>
                                        Role Info
                                    </h4>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('name', 'Role Name', ['class'=>'col-sm-3 col-form-label ', 'disabled' => 'disabled']) !!}
                                    <div class="col-sm-9 mt-2">
                                        <p>{{$role->name}}</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('display_name', 'Display Name', ['class'=>'col-sm-3 col-form-label ', 'disabled' => 'disabled']) !!}
                                    <div class="col-sm-9 mt-2">
                                        <p>{{$role->display_name}}</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('description', 'Description', ['class'=>'col-sm-3 col-form-label ', 'disabled' => 'disabled']) !!}
                                    <div class="col-sm-9 mt-2">
                                        <p>{{$role->description}}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="border-top mt-3 pt-3">
                                <a href="{{route('roles.edit', $role)}}" class="btn btn-warning mr-2">
                                    Edit Role
                                </a>
                                <a href="{{route('roles.index')}}" class="btn btn-outline-secondary">
                                    Back to All Roles
                                </a>
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
                                @foreach ($role->perms as $permission)
                                    {!! Form::inputs('text', 'permissions', $permission->display_name, ['class'=>'form-control mb-2', 'placeholder' => "N/A", 'disabled' => 'disabled']) !!}
                                @endforeach
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