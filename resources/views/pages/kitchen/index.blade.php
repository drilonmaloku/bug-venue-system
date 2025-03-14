@extends('layouts.app')
@section('header')
    {{__('kitchen.title')}}
@endsection

@section('content')
    <style>
        .menu-content p {
            margin-bottom: 0 !important;
        }
    </style>

    <div class="vms_panel">
        <form class="filter-items" action="/kitchen" method="GET">
            <div class="filter-options">
                <div class="huber-filter-btn @if ($is_on_search) active @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter vue-feather__content"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    <p>{{__('general.filter_title')}}</p>
                    <span class="huber-filter-btn-arrow">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L12 15L18 9" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="hubers-filter-options @if ($is_on_search) active @endif">
                <div class="hubers-filter-list-options">
                    <div class="hubers-filter-group">
                        <label>{{__('kitchen.table.filter.menu')}}:</label>
                        <select class="hubers-select-input white medium" name="menu" id="">
                            <option value="">{{__('kitchen.table.filter.select_menu')}}</option>
                            @foreach($menus as $menu)
                                <option value="{{$menu->id}}" @if($menu->id == request('menu')) selected @endif>{{$menu->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="hubers-filter-group">
                        <label>{{__('kitchen.table.filter.start_date')}}:</label>
                        <input class="bug-text-input white medium" type="date" name="start_date" value="{{old('date',request('start_date'))}}">
                    </div>
                    <div class="hubers-filter-group">
                        <label>{{__('kitchen.table.filter.end_date')}}:</label>
                        <input class="bug-text-input white medium" type="date" name="end_date" value="{{old('date',request('end_date'))}}">
                    </div>
                    
                </div>
                <div class="hubers-filter-list-actions">
                    <button type="submit" class="hubers-btn mr-2">{{__('general.filter_btn')}}</button>
                    <a href="/kitchen" class="hubers-btn inverse">{{__('general.filter_reset_btn')}}</a>
                </div>
            </div>
        </form>

        @if(count($orders) > 0)
            <div class="table-responsive">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th>{{__('kitchen.table.date')}}</th>
                        <th>{{__('kitchen.table.menu_name')}}</th>
                        <th>{{__('kitchen.table.menu_contents')}}</th>
                        <th>{{__('kitchen.table.number_of_guests')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($orders as $order)
                        <tr>
                            <td>{{$order->date}}</td>
                            <td>{{$order->menu->name}}</td>
                            <td class="menu-content">{!! $order->menu_contents !!}</td>
                            <td>{{$order->number_of_guests}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            {{$orders->links()}}
        @else
            <div class="hubers-empty-tab">
                @if ($is_on_search)
                    <h5 class="text-center">{{__('kitchen.table.not_found_with_search')}}</h5>
                @else
                    <h5 class="text-center">{{__('kitchen.table.not_found_without_search')}}</h5>
                @endif
            </div>
        @endif
    </div>
@endsection
