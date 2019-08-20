@extends('layouts.blank')

@push('stylesheets')
@endpush

@section('page_header')
    <!-- partial:partials/page_header.blade.php -->
    @include('includes/page_header', [
        'main_title' => 'View Audit Log',
        'header_links_left' => [
            [
                'route' => 'logs.index',
                'text' => 'Audit Logs'
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
                        <div class="card-body">
                            <div class="container-fluid px-0 mb-2">
                                <div class="border-bottom mb-3">
                                    <h4>
                                        Log Info
                                    </h4>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('date_time', 'Date', ['class'=>'col-sm-3 col-form-label']) !!}
                                    <div class="col-sm-9 mt-2">
                                        <p>{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('username', 'User who performed Event', ['class'=>'col-sm-3 col-form-label']) !!}
                                    <div class="col-sm-9 mt-2">
                                        <p>{{ !empty($log->user) ? $log->user->name : ((!empty($log->user_id)) ? 'User ID: #'.$log->user_id.' Deleted' : 'System Console') }}</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('event', 'Database Event', ['class'=>'col-sm-3 col-form-label']) !!}
                                    <div class="col-sm-9 mt-2">
                                        <p>{{ ucfirst($log->actual_event) }}</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('model', 'Object', ['class'=>'col-sm-3 col-form-label']) !!}
                                    <div class="col-sm-9 mt-2">
                                        <p>{{ $log->auditable_type }}</p>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('model', 'Object ID', ['class'=>'col-sm-3 col-form-label']) !!}
                                    <div class="col-sm-9 mt-2">
                                        <p>{{ $log->auditable_id }}</p>
                                    </div>
                                </div>
                                <div class="border-top mt-3 pt-3">
                                    @if(auth()->user()->can('restore-audit') && in_array($log->event, ['deleted', 'updated']))
                                        {!! Form::restorebtn( 'logs', $log->id, 'Restore Object', ['method'=>'POST', 'class'=>'d-inline-block'], 'btn btn-success mr-2' ) !!}
                                    @endif
                                    <a href="{{ route('logs.index') }}" class="btn btn-outline-secondary">
                                        Back to Audit Logs
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
                                    Modified Data
                                </h4>
                            </div>
                            @if(in_array($log_meta['audit_event'], ['attached', 'detached']))
                                <div class="form-group row">
                                    {!! Form::label('relationship', 'Relationship', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                                    <div class="control-data col-md-6 col-sm-6 col-xs-12">
                                        @if($log_meta['audit_event'] == 'attached') {{ ucfirst($log_modified['relation']['new']) }} @else {{ ucfirst($log_modified['relation']['old']) }} @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    @if($log_meta['audit_event'] == 'attached')
                                        {!! Form::label('changes', 'Attached Data', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                                    @else
                                        {!! Form::label('changes', 'Detached', ['class'=>'control-label col-md-3 col-sm-3 col-xs-12']) !!}
                                    @endif    
                                    @if( sizeof($log_modified) > 0 )
                                        <div class="table-responsive col-sm-12">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr class="headings">
                                                        <th class="column-title">@if($log_meta['audit_event'] == 'attached') Related ID @else Removed IDs @endif</th>
                                                        @if($log_meta['audit_event'] == 'attached')<th class="column-title">Attributes</th>@endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach( $log_modified as $field => $modified )
                                                        @if($field !== 'relation')
                                                            <tr>
                                                                @if($log_meta['audit_event'] == 'attached')
                                                                    <td>{{ $field }}</td>
                                                                    <td>@if(empty($modified['new'])) <em>[Null]</em> @else {{ $modified['new'] }} @endif</td>
                                                                @else
                                                                    <td>@if(empty($modified['old'])) <em>[Null]</em> @else {{ $modified['old'] }} @endif</td>
                                                                @endif
                                                            </tr>
                                                        @endif
                                                    @endforeach
                                                </tbody>    
                                            </table>                                        
                                        </div>
                                    @else
                                        <div class="control-data col-sm-12">
                                            <strong><i>No Modified Data Detected</i></strong>
                                        </div>
                                    @endif
                                </div>
                            @endif    
                            @if(in_array($log_meta['audit_event'], ['created', 'deleted', 'updated', 'restored']))
                                <div class="form-group row">
                                    @if( sizeof($log_modified) > 0 )
                                        <div class="table-responsive col-sm-12">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr class="header">
                                                        <th class="column-title">Field</th>
                                                        @if(!in_array($log_meta['audit_event'], ['created', 'restored']))<th class="column-title">Old Value</th>@endif
                                                        @if(!in_array($log_meta['audit_event'], ['deleted']))<th class="column-title">New Value</th>@endif
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach( $log_modified as $field => $modified )
                                                        <tr>
                                                            <td>{{ $field }}</td>
                                                            @if(!in_array($log_meta['audit_event'], ['created', 'restored']))<td>@if(empty($modified['old'])) <em>[Null]</em> @else {{ $modified['old'] }} @endif</td>@endif
                                                            @if(!in_array($log_meta['audit_event'], ['deleted']))<td>@if(empty($modified['new'])) <em>[Null]</em> @else {{ $modified['new'] }} @endif</td>@endif
                                                        </tr>
                                                    @endforeach
                                                </tbody>    
                                            </table>                                        
                                        </div>
                                    @else
                                        <div class="control-data col-sm-12">
                                            <strong><i>No Modified Data Detected</i></strong>
                                        </div>
                                    @endif
                                </div>
                            @endif    
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
@endpush