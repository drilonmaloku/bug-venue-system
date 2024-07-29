@extends('layouts.app')
@section('header')
{{__('menu.title.update')}}: {{$menu->name}}
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
                                    <label for="example-text-input" class="bug-label">{{__('menu.table.name')}}*</label>
                                    <input class="bug-text-input" placeholder="{{__('menu.table.name')}}*" type="text" required  name="name" value="{{$menu->name}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('menu.table.description')}}</label>
                                    <textarea class="bug-text-input" placeholder="{{__('menu.table.description')}}" type="text"  rows="4" name="description" value="{{$menu->description}}">{{$menu->description}}</textarea>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('menu.table.price')}}*</label>
                                    <input type="number" placeholder="{{__('menu.table.price')}}" class="bug-text-input"  name="price" required value="{{$menu->price}}">
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
