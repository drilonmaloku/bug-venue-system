@extends('layouts.app')
@section('header')
    Përditso Menun: {{$menu->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action="{{ route('menus.update',['id'=>$menu->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Emri*</label>
                                    <input class="bug-text-input" placeholder="Emri*" type="text" name="name" value="{{$menu->name}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Qmimi*</label>
                                    <input type="number" placeholder="Qmimi" class="bug-text-input" name="price" required value="{{$menu->price}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Pershkrimi</label>
                                    <input class="bug-text-input" placeholder="Pershkrimi" type="text" name="description" value="{{$menu->description}}">
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
