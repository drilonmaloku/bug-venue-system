@extends('layouts.app')
@section('header')
    Përditso Passwordin: {{ $user->first_name }}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <form role="form" method="POST" action="{{ route('users-password.update',['id'=>$user->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">Fjalëkailimi i vjetër</label>
                                <input class="bug-text-input" type="password" name="password_old">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">Fjalëkailimi i ri</label>
                                <input class="bug-text-input" type="password" name="password_new">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">Konfirmo fjalëkailimi e ri</label>
                                <input class="bug-text-input" type="password" name="password_new_confirmation">
                            </div>
                        </div>
                        <div class="col-md-12"> 
                            <button type="submit" class="hubers-btn">Ruaj</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
