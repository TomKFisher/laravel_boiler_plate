@extends('layouts.auth')

@section('main_container')
    <div class="auto-form-wrapper">
        {!! Form::open(['route'=>'login', 'method'=>'POST']) !!}
            <div class="form-group">
                {!! Form::label('name', 'Email Address', ['class'=>'label']) !!}
                <div class="input-group">
                    {!! Form::input('text', 'email', null, ['class'=>'form-control ', 'placeholder' => "Email"]) !!}
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
                {!! Form::label('name', 'Password', ['class'=>'label']) !!}
                <div class="input-group">
                    {!! Form::input('password', 'password', null, ['class'=>'form-control', 'placeholder' => "*********"]) !!}
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
                {!! Form::button('Login', ['type'=>'submit', 'class'=>'btn btn-primary submit-btn btn-block']) !!}
            </div>
            <div class="form-group text-center">
                <a href="{{route('password.request')}}" class="text-small forgot-password text-black">Forgot Password</a>
            </div>
            <div class="text-block text-center my-3">
                <span class="text-small font-weight-semibold">Not a member ?</span>
                <a href="{{route('register')}}" class="text-black text-small">Create new account</a>
            </div>
        {!! Form::close() !!}
    </div>
    <ul class="auth-footer">
        <li>
            <a href="#">Conditions</a>
        </li>
        <li>
            <a href="#">Help</a>
        </li>
        <li>
            <a href="#">Terms</a>
        </li>
    </ul>
    <p class="footer-text text-center">Copyright © @php echo date('Y') @endphp {{ config('app.name') }}. All rights reserved.</p>
@endsection
