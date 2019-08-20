@extends('layouts.auth', ['bg_class'=>'register-bg-1'])

@section('main_container')
    <h2 class="text-center mb-4">Reset Password</h2>
    <div class="auto-form-wrapper">
        {!! Form::open(['route' => 'password.update', 'method' => 'POST']) !!}
            {!! Form::input('hidden', 'token', $token) !!}
            @error('token')
                <div class="alert alert-danger" role="alert">
                    Your password reset link is now invalid, please click <a href="{{route('password.request')}}" class="alert-link">here</a> to request a new link.
                </div>
            @else
                <p>{{ __('Please enter your email address and new password below and we will reset your password for you.') }}</p>
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
                        <ul class="parsley-errors-list filled mt-2" id="parsley-id-email">
                            <li class="parsley-email pl-2">{{ $message }}</li>
                        </ul>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="input-group">
                        {!! Form::input('password', 'password', null, ['class'=>'form-control', 'placeholder'=>'Password']) !!}
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="mdi mdi-check-circle-outline"></i>
                            </span>
                        </div>
                    </div>
                    @error('password')
                        <ul class="parsley-errors-list filled mt-2" id="parsley-id-password">
                            <li class="parsley-password pl-2">{{ $message }}</li>
                        </ul>
                    @enderror
                </div>
                <div class="form-group">
                    <div class="input-group">
                        {!! Form::input('password', 'password_confirmation', null, ['class'=>'form-control', 'placeholder'=>'Confirm Password']) !!}
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="mdi mdi-check-circle-outline"></i>
                            </span>
                        </div>
                    </div>
                    @error('password_confirmation')
                        <ul class="parsley-errors-list filled mt-2" id="parsley-id-password-confirmation">
                            <li class="parsley-password-confirmation pl-2">{{ $message }}</li>
                        </ul>
                    @enderror
                </div>
                <div class="form-group">
                    {!! Form::button('Reset Password', ['type'=>'submit', 'class'=>'btn btn-primary submit-btn btn-block']) !!}
                </div>
                <div class="text-block text-center my-3">
                    <span class="text-small font-weight-semibold">Remembered your password ?</span>
                    <a href="{{route('login')}}" class="text-black text-small">Login</a>
                </div>
            @endif
        {!! Form::close() !!}
    </div>
@endsection
