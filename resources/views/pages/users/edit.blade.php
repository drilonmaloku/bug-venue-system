@extends('layouts.app')
@section('header')
    Përditso Perdoruesin: {{$user->first_name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action="{{ route('users.update',['id'=>$user->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Username*</label>
                                    <input class="bug-text-input" type="text" name="username" value="{{$user->username}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Emri*</label>
                                    <input class="bug-text-input" type="text" placeholder="Emri" name="first_name" value="{{$user->first_name}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Mbiemri</label>
                                    <input class="bug-text-input" type="text" placeholder="Mbiemri" name="last_name" value="{{$user->last_name}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Emaili*</label>
                                    <input class="bug-text-input" type="text" placeholder="Emaili" name="email" value="{{$user->email}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Telefoni</label>
                                    <input class="bug-text-input" placeholder="Telefoni" type="text" name="phone" value="{{$user->phone}}">
                                </div>
                            </div>
                        
                        </div>
                        <button type="submit" class="hubers-btn">Ruaj</button>


                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
