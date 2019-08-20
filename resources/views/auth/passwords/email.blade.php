@extends('layouts.auth', ['bg_class'=>'register-bg-1'])

@section('main_container')
    <h2 class="text-center mb-4">Reset Password</h2>
    <div class="auto-form-wrapper">
        @if(session('status'))
            <div class="alert alert-success" role="alert">
                {{ session('status') }}
            </div>
        @endif
        {!! Form::open(['route' => 'password.email', 'method' => 'POST']) !!}
            <p>{{ __('Please enter your email address below and we will send you a link with instructions to reset your password.') }}</p>
            <div class="form-group">
                <div class="input-group">
                    {!! Form::input('email', 'email', null, ['class'=>'form-control', 'placeholder'=>'Email Address']) !!}
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                    </div>
                </div>
                @error('email')
                    <ul class="parsley-errors-list filled mt-2" id="parsley-id-password">
                        <li class="parsley-password pl-2">{{ $message }}</li>
                    </ul>
                @enderror
            </div>
            <div class="form-group">
                {!! Form::button('Send Reset Link', ['type'=>'submit', 'class'=>'btn btn-primary submit-btn btn-block']) !!}
            </div>
            <div class="text-block text-center my-3">
                <span class="text-small font-weight-semibold">Remembered your password ?</span>
                <a href="{{route('login')}}" class="text-black text-small">Login</a>
            </div>
        {!! Form::close() !!}
    </div>
@endsection
