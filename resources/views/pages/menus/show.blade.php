@extends('layouts.app')

@section('header')
{{__('menu.title.single')}} : {{$menu->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <button class="btn btn-danger btn-sm ms-auto mb-0" data-toggle="modal" data-target="#deleteModal">
                    <i class="fa fa-trash"></i> {{__('general.delete_btn')}}
                </button>

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
                        <td>{{__('menu.table.description')}}</td>
                        <td>{{ $menu->description }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">{{__('menu.forms.confirm_delete')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{__('menu.forms.delete_confirmation_message')}}
                </div>
                <div class="modal-footer">
                    <form action="{{ route('menus.destroy', $menu->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('general.cancel_btn')}}</button>
                        <button type="submit" class="btn btn-danger">{{__('menu.forms.confirm_delete_btn')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
