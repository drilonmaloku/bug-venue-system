@extends('layouts.app')

@section('header')
Përditso location: {{$location->name}}
@endsection

@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    
                    <form role="form" method="POST" action="{{ route('locations.update',['id'=>$location->id]) }}"   enctype="multipart/form-data" onsubmit="return disableSubmitButton()">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Name</label>
                                    <input class="bug-text-input" type="text" name="name" value="{{$location->name}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Slug</label>
                                    <input class="bug-text-input" type="text" name="slug" value="{{$location->slug}}">
                                </div>
                            </div>
                        </div>
                        <button id="submitBtn" type="submit" class="hubers-btn">Ruaj</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function disableSubmitButton() {
            document.getElementById("submitBtn").disabled = true;
            return true;
        }
    </script>
@endsection

