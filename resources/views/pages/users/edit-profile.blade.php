@extends('layouts.app')
@section('header')
{{__('users.edit.title')}}: {{$user->first_name}} {{$user->last_name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('users.table.username')}}*</label>
                                    <input class="bug-text-input" type="text" name="username" value="{{$user->username}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('users.table.name')}}*</label>
                                    <input class="bug-text-input" required type="text" name="first_name" value="{{$user->first_name}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('users.table.last_name')}}</label>
                                    <input class="bug-text-input" required type="text" name="last_name" value="{{$user->last_name}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('users.table.email')}}</label>
                                    <input class="bug-text-input" type="text" name="email" value="{{$user->email}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('users.table.phone_number')}}</label>
                                    <input class="bug-text-input" type="text" name="phone" value="{{$user->phone}}">
                                </div>
                            </div>
                        
                        </div>
                        <button type="submit" class="hubers-btn">{{__('general.save_btn')}}</button>


                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
