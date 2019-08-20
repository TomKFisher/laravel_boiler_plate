@extends('layouts.blank', [
    'search_route' => 'roles.index',
    'search_append' => []
])

@push('stylesheets')
@endpush

@section('page_header')
    <!-- partial:partials/page_header.blade.php -->
    @include('includes/page_header', [
        'main_title' => 'System Roles',
        'header_links_right' => [
            [
                'route' => 'roles.create',
                'text' => 'Add Role',
                'class' => 'btn btn-success'
            ],[
                'route' => 'roles.archive',
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
                                    <a href="{{ route('roles.show', $role) }}" class="btn btn-info btn-xs">Info</a>
                                    <a href="{{ route('roles.edit', $role) }}" class="btn btn-warning btn-xs">Edit</a>
                                    @if(auth()->user()->can('delete-role') && !auth()->user()->hasRole($role->name))
                                        {{ Form::button('Archive', [
                                            'class' => 'btn btn-danger btn-xs', 
                                            'data-toggle' => 'modal', 
                                            'data-target' => '#archive-confirm', 
                                            'data-role-id' => $role->id, 
                                            'data-role-name' => $role->display_name
                                        ]) }}
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
                        There are no Roles to display. Please <a href="{{route('roles.create')}}" class="alert-link" title="Add a Role">Add a Role.</a>
                    </p>
                </div>
            @endif
        </div>
    </div>
    <div class="modal fade" id="archive-confirm" tabindex="-1" role="dialog" aria-labelledby="archive-confirm-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="archive-confirm-label">Archive Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Are you sure you want to archive the Role <span class="role-name"></span>?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                    {!! Form::deletebtn( 'roles', null, 'Archive', ['method' => 'DELETE', 'class' => 'd-inline-block'], 'btn btn-danger' ) !!}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        jQuery(function($){
            $('#archive-confirm').on('show.bs.modal', function (event) {
                var modal = $(this);
                var button = $(event.relatedTarget);
                var role_id = button.data('role-id');
                var role_name = button.data('role-name');

                modal.find('form').attr('action', '{{route('roles.index')}}/'+role_id);
                modal.find('span.role-name').text(role_name);
            });
        });
    </script>
@endpush