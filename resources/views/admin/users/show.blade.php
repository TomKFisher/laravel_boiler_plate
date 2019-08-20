@extends('layouts.blank', [
    'search_route' => null,
    'search_append' => []
])

@push('stylesheets')
@endpush

@section('page_header')
    <!-- partial:partials/page_header.blade.php -->
    @include('includes/page_header', [
        'main_title' => 'View User',
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
                        <div class='card-body'>
                            <div class="container-fluid px-0 mb-2">
                                <div class="border-bottom mb-3">
                                    <h4>
                                        User Info
                                    </h4>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('name', 'User\'s Name', ['class'=>'col-sm-3 col-form-label ', 'disabled' => 'disabled']) !!}
                                    <div class="col-sm-9 mt-2">
                                        <p>{{$user->name}}</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('name', 'Email', ['class'=>'col-sm-3 col-form-label ', 'disabled' => 'disabled']) !!}
                                    <div class="col-sm-9 mt-2">
                                        <p>{{$user->email}}</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('name', 'Email Verification Status', ['class'=>'col-sm-3 col-form-label ', 'disabled' => 'disabled']) !!}
                                    <div class="col-sm-9 mt-2">
                                        <p>{{ (!empty($user->email_verified_at)) ? 'Email Verified' : 'Not Verified'}}</p>
                                    </div>
                                </div>
                                <div class="border-top mt-3 pt-3">
                                    <a href="{{route('users.edit', $user)}}" class="btn btn-warning mr-2">
                                        Edit User
                                    </a>
                                    @if(!empty($user->email_verified_at))
                                        {!! Form::open(['route' => ['users.password_reset', $user], 'method'=>'POST', 'class'=>'d-inline-block mr-2'])!!}
                                            {!! Form::button('Send Reset Password Link', ['type'=>'Submit', 'class'=>'btn btn-outline-primary'])!!}
                                        {!! Form::close() !!}
                                    @else
                                        {!! Form::open(['route' => ['users.re_invite', $user], 'method'=>'POST', 'class'=>'d-inline-block mr-2'])!!}
                                            {!! Form::button('Resend Invite', ['type'=>'Submit', 'class'=>'btn btn-outline-primary'])!!}
                                        {!! Form::close() !!}
                                    @endif
                                    <a href="{{route('users.index')}}" class="btn btn-outline-secondary">
                                        Back to User Management
                                    </a>
                                </div>
                            </div>
                        </div>
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
                                    Assigned User Roles
                                </h4>
                            </div>
                            <div class="form-group">
                                @foreach( $user['roles'] as $role )
                                    {!! Form::inputs('text', 'role', $role['display_name'], ['class'=>'form-control', 'placeholder' => "N/A", 'disabled' => 'disabled']) !!}
                                @endforeach
                            </div>
                            <div class="border-bottom mb-3">
                                <h4>
                                    Assigned Permissions
                                </h4>
                            </div>
                            <div class="form-group">
                                @foreach( $user->getAllPermissions() as $permission )
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