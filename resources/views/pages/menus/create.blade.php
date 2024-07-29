@extends('layouts.app')

@section('header')
    {{__('menu.forms.create_title')}}
@endsection

@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <form role="form" method="POST" action="{{ route('menus.store') }}" enctype="multipart/form-data" onsubmit="return disableSubmitButton()">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('menu.table.name')}}*</label>
                                <input class="bug-text-input" type="text" placeholder="{{__('menu.table.name')}}*" required name="name">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('menu.table.description')}}</label>
                                <textarea class="bug-text-input" placeholder="{{__('menu.table.description')}}"  rows="4" name="description"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('menu.table.price')}}*</label>
                                <input class="bug-text-input" placeholder="{{__('menu.table.price')}}" required type="number" name="price">
                            </div>
                        </div>
                    </div>
                    <button id="submitBtn" type="submit" class="hubers-btn">{{__('general.save_btn')}}</button>
                </form>
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
