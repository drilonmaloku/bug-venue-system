@extends('layouts.app')
@section('header')
    {{__('clients.forms.update_title')}}: {{$client->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action="{{ route('clients.update',['id'=>$client->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('clients.table.name')}}*</label>
                                    <input class="bug-text-input" required type="text" name="name" value="{{$client->name}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('clients.table.phone_number')}}*</label>
                                    <input class="bug-text-input" required type="text" name="phone_number" value="{{$client->phone_number}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('clients.table.additional_phone_number')}}</label>
                                    <input class="bug-text-input" type="text" name="additional_phone_number" value="{{$client->additional_phone_number}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('clients.table.email')}}</label>
                                    <input type="email" class="bug-text-input" name="email" value="{{$client->email}}" />
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
