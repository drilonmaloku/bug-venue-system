@extends('layouts.app')

@section('header')
    Onboard
@endsection
@section('content')
    <div class="vms_panel">
        <form role="form" method="POST" action={{ route('onboard.store') }} enctype="multipart/form-data" onsubmit="return disableSubmitButton();">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <h5>{{__('venues.forms.create_title')}}</h5>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('venues.table.name')}}*</label>
                                <input class="bug-text-input" type="text" required name="venue_name" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('venues.table.description')}}</label>
                                <textarea class="bug-text-input" rows="4" name="venue_description" ></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('venues.table.capacity')}}*</label>
                                <input class="bug-text-input" type="number" required name="venue_capacity">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12">
                    <h5> {{__('menu.forms.create_title')}}</h5>
                </div>
                <div class="col-md-8">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('menu.table.name')}}*</label>
                                <input class="bug-text-input" type="text" placeholder="{{__('menu.table.name')}}*" required name="menu_name">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('menu.table.description')}}</label>
                                <textarea class="bug-text-input" placeholder="{{__('menu.table.description')}}"  rows="4" name="menu_description"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('menu.table.price')}}*</label>
                                <input class="bug-text-input" placeholder="{{__('menu.table.price')}}" required type="number" name="menu_price">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button id="submitBtn" type="submit" class="hubers-btn">{{__('venues.forms.save')}}</button>
        </form>
    </div>
    <script>
        function disableSubmitButton() {
            document.getElementById("submitBtn").disabled = true;
            return true;
        }
    </script>
@endsection
