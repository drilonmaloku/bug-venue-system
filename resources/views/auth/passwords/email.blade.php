@extends('layouts.app')

@section('content')
    <div class="vms-login-view">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="vms_panel">
                        <a class="login-logo" href="https://bugagency.tech" target="_blank">
                            <img src="https://bugagency.tech/wp-content/uploads/assets/logo_main.png" alt="" />
                            Reset Password
                        </a>
                        <hr>
                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="form-group">
                                <label for="email" class="bug-label">Email*</label>
                                <input id="email" type="text" class="bug-text-input @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required  autofocus>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="hubers-btn login-btn">
                                    Submit
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
