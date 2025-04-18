@extends('layouts.app')

@section('content')
    <div class="vms-login-view">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="vms_panel">
                        <a class="login-logo" href="https://bugagency.tech" target="_blank">
                            <img src="https://bugagency.tech/wp-content/uploads/assets/logo_main.png" alt="" />
                            Venue Management Solution by BugAgency
                        </a>
                        <hr>
                        <h3 class="text-center">{{ __('Reset Password') }}</h3>

                        <form method="POST" action="{{ route('password.update') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">

                            <!-- Add Email Field -->
                            <div class="form-group">
                                <label for="email" class="bug-label">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="bug-text-input @error('email') is-invalid @enderror" 
                                       name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <!-- New Password Field -->
                            <div class="form-group">
                                <label for="password" class="bug-label">{{ __('New Password') }}</label>
                                <input id="password" type="password" class="bug-text-input @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>

                            <!-- Confirm New Password Field -->
                            <div class="form-group">
                                <label for="password-confirm" class="bug-label">{{ __('Confirm New Password') }}</label>
                                <input id="password-confirm" type="password" class="bug-text-input" name="password_confirmation" required autocomplete="new-password">
                            </div>

                            <!-- Submit Button -->
                            <div class="form-group">
                                <button type="submit" class="hubers-btn login-btn">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
