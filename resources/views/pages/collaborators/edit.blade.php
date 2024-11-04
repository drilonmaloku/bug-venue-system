@extends('layouts.app')
@section('header')
    Përditso Dekorin: {{$collaborator->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action="{{ route('collaborators.update',['id'=>$collaborator->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Emri*</label>
                                    <input class="bug-text-input" type="text" name="name" value="{{$collaborator->name}}">
                                </div>
                            </div>
                               <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Email</label>
                                    <input class="bug-text-input" type="text" name="email" value="{{$collaborator->email}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Numri i Telefonit</label>
                                    <input class="bug-text-input" type="text" name="phone_number" value="{{$collaborator->phone_number}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Lloji</label>
                                    <input class="bug-text-input" type="text" name="typeof" value="{{$collaborator->typeof}}">
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
