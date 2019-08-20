@extends('layouts.blank', [
    'search_route' => 'users.archive',
    'search_append' => []
])

@push('stylesheets')
@endpush

@section('page_header')
    <!-- partial:partials/page_header.blade.php -->
    @include('includes/page_header', [
        'main_title' => 'Archived Users',
        'header_links_left' => [
            [
                'route' => 'users.index',
                'text' => 'User Management'
            ]
        ],
        'header_links_right' => [
            [
                'route' => 'users.index',
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
            @if( isset($users) && !empty($users) && sizeof($users) > 0 )
                <div class="table-responsive">
                    <table class="table table-striped table-bordered">
                        <thead>
                        <tr class="header">
                            <th class="column-title">Name</th>
                            <th class="column-title">Email</th>
                            <th class="column-title">Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->name }} </td>
                                <td>{{ $user->email }}</td>
                                <td class="restore-delete">
                                    {!! Form::restorebtn( 'users', $user->id, 'Restore' ) !!}
                                    {!! Form::button('Delete', [
                                        'class' => 'btn btn-danger btn-xs', 
                                        'data-toggle' => 'modal', 
                                        'data-target' => '#delete-confirm', 
                                        'data-user-id' => $user->id, 
                                        'data-user-email' => $user->email
                                    ]) !!}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-danger mb-0" role="alert">
                    <p class="mb-0">
                        There are no archived Users to display. <a href="{{route('users.index')}}" class="alert-link" title="Back to User Management">Back to User Management.</a>
                    </p>
                </div>
            @endif
        </div>
    </div>
    <div class="modal fade" id="delete-confirm" tabindex="-1" role="dialog" aria-labelledby="delete-confirm-label" aria-hidden="true">
        <div class="modal-dialog" role="document">
            {!! Form::open(['class'=>'modal-content']) !!}
                <div class="modal-header">
                    <h5 class="modal-title" id="delete-confirm-label">Permanently Delete User</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to permanently delete this User, <span class="user-email"></span>?</p>
                    <p>Please select an alternative User to re-assign ownership of all records held in the system:</p>
                    {!! Form::selects('new_user_id', [''=>'Please select new User'], null, ['class'=>'new-user form-control']) !!}
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
                var user_id = button.data('user-id');
                var user_email = button.data('user-email');

                modal.find('form').attr('action', '{{route('users.index')}}/'+user_id+'/delete');
                modal.find('span.user-email').text(user_email);
            });
            $('#delete-confirm').on('hidden.bs.modal', function (event) {
                var modal = $(this);
                modal.find('form').attr('action', '');
                modal.find('span.user-email').text('');
            });
            $('.new-user').select2({
                ajax: {
                    url: '{{route('users.search')}}',
                    dataType: 'json',
                    data: function (params) {
                        var query = {
                            search: params.term,
                            page: params.page || 1
                        }

                        return query;
                    }
                }
            });
        });
    </script>
@endpush