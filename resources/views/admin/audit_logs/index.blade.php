@extends('layouts.blank', [
    'search_route' => 'logs.index'
])

@push('stylesheets')
@endpush

@section('page_header')
    <!-- partial:partials/page_header.blade.php -->
    @include('includes/page_header', [
        'main_title' => 'Audit Logs'
    ])
    <!-- partial -->
@endsection

@section('main_container')
    <div class="card">
        <div class="card-body">
            @if( isset($logs) && !empty($logs) && sizeof($logs) > 0 )
                <div class="table-responsive mb-3">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr class="header">
                                <th class="column-title">Date / Time</th>
                                <th class="column-title">User</th>
                                <th class="column-title">Event</th>
                                <th class="column-title">Object</th>
                                <th class="column-title">Object ID</th>
                                <th class="column-title" width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($logs as $log)
                                <tr>
                                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                                    <td>{{ !empty($log->user) ? $log->user->name : ((!empty($log->user_id)) ? 'User ID: #'.$log->user_id.' Deleted' : 'System Console') }}</td>
                                    <td>{{ ucfirst($log->actual_event) }}</td>
                                    <td>{{ $log->auditable_type }}</td>
                                    <td>{{ $log->auditable_id }}</td>
                                    <td class="column-actions">
                                        <a href="{{ route('logs.show', $log) }}" class="btn btn-info btn-xs">Info</a>
                                        @if(auth()->user()->can('restore-audit') && in_array($log->event, ['deleted', 'updated']))
                                            {!! Form::restorebtn( 'logs', $log->id, 'Restore Object' ) !!}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $logs->appends(['s'=>$s])->links() }}
            @else
                <div class="alert alert-danger mb-0" role="alert">
                    <p class="mb-0">
                        There are no Audit Logs to display.
                    </p>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
@endpush