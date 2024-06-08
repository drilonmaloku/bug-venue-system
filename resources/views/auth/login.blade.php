@extends('layouts.app')

@section('content')
    <div class="vms-login-view">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="vms_panel">
                        <a class="login-logo" href="https://bugagency.tech" target="_blank">
                            <img src="https://bugagency.tech/wp-content/uploads/assets/logo_main.png" alt="" />
                            Venue Managment Solution by BugAgency
                        </a>
                        <hr>
                        <form method="POST" action="{{ route('loginv2') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email" class="bug-label">Email*</label>
                                <input id="email" type="text" class="bug-text-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required  autofocus>
                            </div>
                            <div class="form-group">
                                <label for="password" class="bug-label">Password*</label>
                                <input id="password" type="password" class="bug-text-input @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
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
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
