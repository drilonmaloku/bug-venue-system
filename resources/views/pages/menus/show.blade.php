@extends('layouts.app')

@section('header')
{{__('menu.title.single')}} : {{$menu->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">

                    <form action="{{ route('menus.destroy', $menu->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm ms-auto mb-0" type="submit"><i class="fa fa-trash" data-confirm-delete="true"></i> {{__('general.delete_btn')}}</button>
                    </form>

                <div class="bug-table-item-options">
                    <a class="bug-table-item-option" href="{{route('menus.edit',['id'=>$menu->id])}}">
                        <i class="fa fa-edit"></i>
                    </a>
                </div>
                <table>
                    <thead>
                    <tr>
                        <th colspan="2">{{__('menu.title.information')}}:</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{__('menu.table.name')}}</td>
                        <td>{{ $menu->name }}</td>
                    </tr>
                    <tr>
                        <td>{{__('menu.table.price')}}</td>
                        <td>{{ $menu->price }}</td>
                    </tr>
                    <tr>
                        <td>{{__('menu.title.description')}}</td>
                        <td>{{ $menu->description }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
