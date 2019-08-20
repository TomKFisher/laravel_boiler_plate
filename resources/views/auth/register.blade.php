@extends('layouts.auth', ['bg_class'=>'register-bg-1'])

@section('main_container')
    <h2 class="text-center mb-4">Register</h2>
    <div class="auto-form-wrapper">
        {!! Form::open(['route' => 'register', 'method' => 'POST']) !!}
            <div class="form-group">
                <div class="input-group">
                    {!! Form::input('text', 'name', null, ['class'=>'form-control', 'placeholder'=>'Name']) !!}
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="mdi mdi-check-circle-outline"></i>
                        </span>
                    </div>
                </div>
                @error('name')
                    <ul class="parsley-errors-list filled mt-2" id="parsley-id-password">
                        <li class="parsley-password pl-2">{{ $message }}</li>
                    </ul>
                @enderror
            </div>
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
                    <ul class="parsley-errors-list filled mt-2" id="parsley-id-password">
                        <li class="parsley-password pl-2">{{ $message }}</li>
                    </ul>
                @enderror
            </div>
            <div class="form-group">
                {!! Form::button('Register', ['type'=>'submit', 'class'=>'btn btn-primary submit-btn btn-block']) !!}
            </div>
            <div class="text-block text-center my-3">
                <span class="text-small font-weight-semibold">Already have an account ?</span>
                <a href="{{route('login')}}" class="text-black text-small">Login</a>
            </div>
        {!! Form::close() !!}
    </div>
@endsection
