@extends('layouts.app')
@section('header')
    Përditso Sallen: {{$venue->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action="{{ route('venues.update',['id'=>$venue->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Emri*</label>
                                    <input class="bug-text-input" type="text" name="name" value="{{$venue->name}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Përshkrimi</label>
                                    <textarea class="bug-text-input" rows="4" name="description" >{{$venue->description}}</textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Kapaciteti</label>
                                    <input class="bug-text-input" type="number" name="capacity" value="{{$venue->capacity}}">
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
