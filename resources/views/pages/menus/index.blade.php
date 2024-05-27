@extends('layouts.app')
@section('header')
    Menut
@endsection
@section('header-actions')
    <a class="hubers-btn" href="{{route('menus.create')}}">Krijo</a>
@endsection
@section('content')
    <div class="vms_panel">
        <form action="/menus" method="GET" >
            <div class="hubers-filter-options">
                <div class="hubers-filter-list-options">
                    <div class="hubers-filter-group">
                        <label>Search:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="text" name="search" value="{{ request('search') }}">
                    </div>
                </div>
                <div class="hubers-filter-list-actions">
                    <button type="submit" class="hubers-btn mr-2">Filtro</button>
                    <a href="/menus" class="hubers-btn inverse">Reset</a>
                </div>
            </div>
        </form>
        @if(count($menus) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th>Emri</th>
                        <th>Qmimi</th>
                        <th>Pershkrimi</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($menus as $menu)
                        <tr>
                            <td>
                                {{$menu->name}}
                            </td>
                            <td>
                                {{$menu->price}}
                            </td>
                            <td>
                                {{$menu->description}}
                            </td>
                            <td>
                                <div class="bug-table-item-options">
                                    <a class="bug-table-item-option" href="{{route('menus.view',['id'=>$menu->id])}}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a class="bug-table-item-option" href="{{route('menus.edit',['id'=>$menu->id])}}">
                                        <i class="fa fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="hubers-empty-tab">
                @if ($is_on_search)
                    <h5 class="text-center">Nuk ka menu sipas search</h5>
                    @else
                    <h5 class="text-center">Nuk ka menu momentalisht</h5>
                @endif
            </div>
        @endif
    </div>

@endsection
