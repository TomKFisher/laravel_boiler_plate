@extends('layouts.blank', [
    'search_route' => 'roles.index',
    'search_append' => []
])

@push('stylesheets')
@endpush

@section('page_header')
    <!-- partial:partials/page_header.blade.php -->
    @include('includes/page_header', [
        'main_title' => 'Archived Roles',
        'header_links_left' => [
            [
                'route' => 'roles.index',
                'text' => 'System Roles'
            ]
        ],
        'header_links_right' => [
            [
                'route' => 'roles.index',
                'text' => 'Active',
                'class' => 'btn btn-outline-primary'
            ]
        ]
    ])
    <!-- partial -->
@endsection

@section('main_container')
    <div class="card">
        <div class="card-body">
            @if( isset($roles) && !empty($roles) && sizeof($roles) > 0 )
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr class="header">
                            <th class="column-title">Name</th>
                            <th class="column-title">Description</th>
                            <th class="column-title">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($roles as $role)
                            <tr>
                                <td>{{ $role['display_name'] }}</td>
                                <td>{{ $role['description'] }}</td>
                                <td class="info-edit-archive">
                                    @if(auth()->user()->can('delete-role'))
                                        {!! Form::restorebtn( 'roles', $role->id, 'Restore' ) !!}
                                        {!! Form::button('Delete', [
                                            'class' => 'btn btn-danger btn-xs', 
                                            'data-toggle' => 'modal', 
                                            'data-target' => '#delete-confirm', 
                                            'data-role-id' => $role->id, 
                                            'data-role-name' => $role->display_name
                                        ]) !!}
                                    @endif                                    
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-danger mb-0" role="alert">
                    <p class="mb-0">
                        There are no archived Roles to display. <a href="{{route('roles.index')}}" class="alert-link" title="Back to Roles Index">Back to Roles Index.</a>
                    </p>
                </div>
            @endif
        </div>
    </div>
    <div class="modal fade" id="delete-confirm" tabindex="-1" role="dialog" aria-labelledby="delete-confirm-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            {!! Form::open(['class'=>'modal-content']) !!}
                <div class="modal-header">
                    <h5 class="modal-title" id="delete-confirm-label">Permanently Delete Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to permanently delete the Role <span class="role-name"></span>?</p>
                    <p>If required please select an alternative Role to re-assign to Users:</p>
                    {!! Form::selects('new_role_id', $active_roles, null, ['class'=>'form-control']) !!}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-danger">Delete</button>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        jQuery(function($){
            $('#delete-confirm').on('show.bs.modal', function (event) {
                var modal = $(this);
                var button = $(event.relatedTarget);
                var role_id = button.data('role-id');
                var role_name = button.data('role-name');

                modal.find('form').attr('action', '{{route('roles.index')}}/'+role_id+'/delete');
                modal.find('span.role-name').text(role_name);
            });
            $('#delete-confirm').on('hidden.bs.modal', function (event) {
                var modal = $(this);
                modal.find('form').attr('action', '');
                modal.find('span.role-name').text('');
            });
        });
    </script>
@endpush