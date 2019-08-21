@extends('layouts.blank', [
    'search_route' => 'logs.index'
])

@push('stylesheets')
@endpush

@section('page_header')
    <!-- partial:partials/page_header.blade.php -->
    @include('includes/page_header', [
        'main_title' => 'REST API Key'
    ])
    <!-- partial -->
@endsection

@section('main_container')
    <div class="card">
        <div class="card-body">
            @if(!empty($client))
                <p>Please see below your REST API key for use.</p>
                <div class="form-group row">
                    {!! Form::label('id', 'Key ID', ['class'=>'col-sm-3 col-form-label required', 'disabled' => 'disabled']) !!}
                    <div class="col-sm-12">
                        {!! Form::inputs('text', 'id', $client->id, ['class'=>'form-control', 'readonly' => 'readonly']) !!}
                    </div>
                </div>
                <div class="form-group row">
                    {!! Form::label('id', 'Secret', ['class'=>'col-sm-3 col-form-label required', 'disabled' => 'disabled']) !!}    
                    <div class="input-group col-sm-12">
                        {!! Form::input('password', 'secret', $client->secret, ['class' => 'api-secret form-control', 'readonly' => 'readonly']) !!}
                        <span class="input-group-append">
                            {!! Form::button('Show', ['class' => 'view-key btn btn-info']) !!}
                            {!! Form::button('Revoke', ['class' => 'btn btn-danger', 'data-toggle' => 'modal', 'data-target' => '#revoke-confirm']) !!}
                        </span>
                    </div>
                </div>
            @else
                <p>Please generate a new REST API key here.</p>
                <p>You will need an active API key in order for the App to work</p>
                {!! Form::open(['route' => 'api-key.generate']) !!}
                    {!! Form::button('Generate API Key', ['type' => 'submit', 'class' => 'btn btn-info']) !!}
                {!! Form::close() !!}
            @endif
        </div>
    </div>
    @if(!empty($client))
        <div class="modal fade" id="revoke-confirm" tabindex="-1" role="dialog" aria-labelledby="revoke-confirm-label" aria-hidden="true">
            <div class="modal-dialog" role="document">
                {!! Form::open(['route'=>['api-key.revoke', $client], 'class'=>'modal-content']) !!}
                    <div class="modal-header">
                        <h5 class="modal-title" id="delete-confirm-label">Revoke API Key</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to revoke your REST API key?</p>
                        <p>By revoking your key you will prevent all current App users from being able to access funtionality on the App.</p>
                        <p>App Users will have to re-setup the App from scratch</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Revoke</button>
                    </div>
                {!! Form::close() !!}
            </div>
        </div>
    @endif
@endsection

@push('scripts')
    <script type="text/javascript">
        jQuery(function($){
            $('.btn.view-key').on('click', function(){
                if($('input.api-secret').attr('type') == 'password'){
                    $('input.api-secret').attr('type', 'text');
                    $(this).html('Hide');
                }else{
                    $('input.api-secret').attr('type', 'password');
                    $(this).html('Show');
                }
            });
        });
    </script>
@endpush