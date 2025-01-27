@extends('layouts.app')

@section('content')
    <div class="vms-login-view">
        <div class="container">
            @foreach($errors as $error)
                test
            @endforeach
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="vms_panel">
                        <a class="login-logo" href="https://bugagency.tech" target="_blank">
                            <img src="https://bugagency.tech/wp-content/uploads/assets/logo_main.png" alt="" />
                            Venue Managment Solution by BugAgency
                        </a>
                        <hr>
                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email" class="bug-label">Email or Username*</label>
                                <input id="email" type="text" class="bug-text-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required  autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password" class="bug-label">Password*</label>
                                <div class="password-input-toggle">
                                    <input id="password" type="password" class="bug-text-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                    <span class="password-input-toggle-icon"><i class="fa fa-eye"></i></span>
                                </div>
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="hubers-btn login-btn">
                                    {{ __('Login') }}
                                </button>
                            </div>
                            <div class="form-group text-center">
                                <a href="{{ route('password.request') }}" class="forgot-password-link">
                                    {{ __('Forgot Your Password?') }}
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Reset Password') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
