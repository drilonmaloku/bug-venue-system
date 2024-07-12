@extends('layouts.app')
@section('header')
    Përditso Passwordin: {{ $user->first_name }}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form role="form" method="POST" action="{{ route('users-password.update', ['id' => $user->id]) }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-md-6">

                            <label for="example-text-input" class="bug-label">Fjalëkailimi i vjetër</label>
                            <div class="password-input-toggle">
                                <input id="password" type="password"
                                    class="bug-text-input @error('password') is-invalid @enderror" name="password_old"
                                    required autocomplete="current-password">
                                <span class="password-input-toggle-icon"><i class="fa fa-eye"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <label for="example-text-input" class="bug-label">Fjalëkailimi i ri</label>
                            <div class="password-input-toggle">
                                <input id="password" type="password"
                                    class="bug-text-input @error('password') is-invalid @enderror" name="password_new"
                                    required autocomplete="current-password">
                                <span class="password-input-toggle-icon"><i class="fa fa-eye"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6">

                            <label for="example-text-input" class="bug-label">Konfirmo fjalëkailimi e ri</label>
                            <div class="password-input-toggle ">

                                <input id="password" type="password"
                                    class="bug-text-input @error('password') is-invalid @enderror"
                                    name="password_new_confirmation" required autocomplete="current-password">
                                <span class="password-input-toggle-icon"><i class="fa fa-eye"></i></span>
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <button type="submit" class="hubers-btn">Ruaj</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
