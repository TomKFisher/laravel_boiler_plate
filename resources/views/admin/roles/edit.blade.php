@extends('layouts.blank')

@push('stylesheets')
@endpush

@section('page_header')
    <!-- partial:partials/page_header.blade.php -->
    @include('includes/page_header', [
        'main_title' => 'Edit Role',
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
                        {!! Form::model($role, ['route'=>['roles.update', $role], 'method'=>'PUT', 'id'=>'create-form', 'class'=>'card-body', 'data-parsley-validate'=>'', 'novalidate'=>'']) !!}
                            <div class="border-bottom mb-3">
                                <h4>
                                    Role Details
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
                            <div class="form-group row">
                                <div class="col-sm-6">
                                    {!! Form::select('permissions', $perms, null, ['id'=>'permission', 'class'=>'permissions select2 form-control', 'style' => 'width:100%']) !!}
                                </div>
                                <div class="col-sm-6">
                                    {!! Form::button('Add Permission', ['id'=>'add-permission', 'type'=>'button', 'class'=>'btn btn-success']) !!}
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-6 roles-edit-permissions">
                                    @foreach( $role->perms as $perm )
                                        <div class="alert alert-info fade show d-flex align-items-center flex-nowrap pr-1 py-1 mb-2" role="alert">
                                            <span>{{ $perm['display_name'] }}</span>
                                            <button type="button" class="remove-perm close px-2 ml-auto" aria-label="Close" style="font-size:1.2rem;" data-perm-id="{{ $perm['id'] }}" data-dismiss="alert">
                                                <span aria-hidden="true"><i class="fa fa-times"></i></span>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        var roles_edit_permissions_url = '{{ route('roles.edit_permissions', $role['id']) }}',
            role_id = '{{ $role['id'] }}',
            csrf_token = '{{ csrf_token() }}';

        jQuery(function ($) {
            $('#add-permission').on('click', function () {
                var $permission_id = $('#permission').val();
                $.ajax({
                    url: roles_edit_permissions_url,
                    method: 'POST',
                    data: {
                        'role_id': role_id,
                        'permission_id': $permission_id,
                        'action': 'add',
                        '_token': csrf_token
                    },
                    success: function (data) {
                        if (data.success == 'true') {
                            var $append_str = '<div class="alert alert-info fade show d-flex align-items-center flex-nowrap pr-1 py-1 mb-2" role="alert">' +
                                '<span>' + data.perm.display_name + '</span>' +
                                '<button type="button" class="remove-perm close px-2 ml-auto" aria-label="Close" style="font-size:1.2rem;" data-perm-id="' + data.perm.id + '" data-dismiss="alert"><span aria-hidden="true"><i class="fa fa-times"></i></span></button>' +
                            '</div>';
                            $('.roles-edit-permissions').append($append_str);
                            $('#permission option:selected').remove();
                        } else {
                            new PNotify({
                                title: 'Error - Adding Permission',
                                text: data.msg,
                                type: 'error',
                                styling: 'bootstrap3'
                            });
                        }
                    }
                })
            });

            $('.remove-perm').on('click', function () {
                var $pill = $(this);
                var $permission_id = $pill.attr('data-perm-id');
                $.ajax({
                    url: roles_edit_permissions_url,
                    method: 'POST',
                    data: {
                        'role_id': role_id,
                        'permission_id': $permission_id,
                        'action': 'remove',
                        '_token': csrf_token
                    },
                    success: function (data) {
                        if (data.success == 'true') {
                            $('#permission').append('<option value="' + data.perm.id + '">' + data.perm.display_name + '</option>');
                            $pill.parent().fadeOut('slow', function () {
                                $(this).remove();
                            });
                        } else {
                            new PNotify({
                                title: 'Error - Removing Permission',
                                text: data.msg,
                                type: 'error',
                                styling: 'bootstrap3'
                            });
                        }
                    }
                })
            });
        });
    </script>
@endpush