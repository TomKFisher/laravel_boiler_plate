@extends('layouts.auth', ['bg_class'=>'register-bg-1'])

@section('main_container')
    <h2 class="text-center mb-4">Register</h2>
    <div class="auto-form-wrapper p-0">
        <div class="border-bottom py-3 px-4">{{ __('Verify Your Email Address') }}</div>
        <div class="py-3 px-4">
            @if (session('resent'))
                <div class="alert alert-success" role="alert">
                    {{ __('A fresh verification link has been sent to your email address.') }}
                </div>
            @endif

            <p>{{ __('Before proceeding, please check your email for a verification link.') }}</p>
            <p>{{ __('If you did not receive the email') }}, <a href="{{ route('verification.resend') }}">{{ __('click here to request another') }}</a>.</p>
        </div>
        <div class="text-block text-center pb-3">
            <span class="text-small font-weight-semibold">Here by mistake ?</span>
            <a href="{{route('logout')}}" class="text-black text-small">Logout</a>
        </div>
    </div>
@endsection
