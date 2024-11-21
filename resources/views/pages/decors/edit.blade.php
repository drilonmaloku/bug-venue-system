@extends('layouts.app')
@section('header')
    Përditso Dekorin: {{$decor->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action="{{ route('decors.update',['id'=>$decor->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Emri*</label>
                                    <input class="bug-text-input" type="text" name="name" value="{{$decor->name}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                   
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Pershkrimi</label>
                                    <input class="bug-text-input" type="text" name="description" value="{{$decor->description}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Foto</label>
                                    @if(!empty($decor->image_url))
                                        <div>
                                            <img src="{{$decor->image_url }}" alt="Existing Image" style="max-width: 150px; height: auto;">
                                        </div>
                                    @endif

                                    <label for="image">Perditso</label>
                                    <input class="bug-text-input" type="file" name="image" id="image">
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
