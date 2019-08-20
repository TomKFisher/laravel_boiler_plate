@extends('layouts.blank', [
    'search_route' => 'users.index',
    'search_append' => []
])

@push('stylesheets')
@endpush

@section('page_header')
    <!-- partial:partials/page_header.blade.php -->
    @include('includes/page_header', [
        'main_title' => 'User Management',
        'header_links_right' => [
            [
                'route' => 'users.create',
                'text' => 'Add User',
                'class' => 'btn btn-success'
            ],[
                'route' => 'users.archive',
                'text' => 'Archive',
                'class' => 'btn btn-outline-secondary'
            ]
        ]
    ])
    <!-- partial -->
@endsection

@section('main_container')
    <div class="card">
        <div class="card-body">
            @if( isset($users) && !empty($users) && sizeof($users) > 0 )
                <div class="table-responsive mb-3">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr class="header">
                            <th class="column-title">Name</th>
                            <th class="column-title">Email / Username</th>
                            <th class="column-title">Role(s)</th>
                            <th class="column-title">Status</th>
                            <th class="column-title text-center">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }} </td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->roles->implode('display_name', ', ') }}</td>
                                <td>{{ (!empty($user->email_verified_at)) ? 'Email Verified' : 'Not Verified'}}</td>
                                <td class="info-edit-archive-invite">
                                    <a href="{{ route('users.show', $user) }}" class="btn btn-info btn-xs">Info</a>
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning btn-xs">Edit</a>
                                    @if(!empty($user->email_verified_at))
                                        {!! Form::open(['route' => ['users.password_reset', $user], 'method'=>'POST', 'class'=>'d-inline-block'])!!}
                                            {!! Form::button('Send Reset Password Link', ['type'=>'Submit', 'class'=>'btn btn-outline-secondary btn-xs'])!!}
                                        {!! Form::close() !!}
                                    @else
                                        {!! Form::open(['route' => ['users.re_invite', $user], 'method'=>'POST', 'class'=>'d-inline-block'])!!}
                                            {!! Form::button('Resend Invite', ['type'=>'Submit', 'class'=>'btn btn-outline-secondary btn-xs'])!!}
                                        {!! Form::close() !!}
                                    @endif
                                    @if( auth()->user()->id != $user->id )
                                        {!! Form::deletebtn( 'users', $user->id, 'Archive' ) !!}
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $users->links() }}
            @else
                <div class="alert alert-danger mb-0" role="alert">
                    <p>
                        There are no User Accounts to display. Please <a href="{{route('users.create')}}" class="alert-link" title="Add a User">Add a User Account</a>.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
@endpush