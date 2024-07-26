@extends('layouts.app')
@section('header')
    {{__('reservations.title')}}
@endsection
@section('header-actions')
    @if(count($menus) > 0 && count($venues) > 0)
    <a class="hubers-btn" href="{{route('reservations.create')}}">{{__('reservations.create_btn')}}</a>
    @endif
@endsection
@section('content')
    <div class="vms_panel">
        <form class="filter-items" action="/reservations" method="GET" >
            <div class="filter-options">
                <div class="huber-filter-btn  @if ($is_on_search) active @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter vue-feather__content"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    <p>{{__('general.filter_title')}}</p>
                    <span class="huber-filter-btn-arrow">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L12 15L18 9" stroke="black" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
                <div class="export-options" onclick="exportOptions.export('/reservations/export','reservations-export.xlsx')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
            </div>
            <div class="hubers-filter-options @if ($is_on_search) active @endif">
                <div class="hubers-filter-list-options">
                    <div class="hubers-filter-group">
                        <label>{{__('reservations.table.filter.search')}}:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="text" name="search" value="{{ request('search') }}">
                    </div>
                    <div class="hubers-filter-group">
                        <label>{{__('reservations.table.filter.date')}}:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="date" name="date" value="{{old('date',app('request')->input('date'))}}">
                    </div>

                    <div class="hubers-filter-group">
                        <label>{{__('reservations.table.filter.created_date')}}:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="date" name="created_at" value="{{ old('created_at', app('request')->input('created_at')) }}">
                    </div>
                    <div class="hubers-filter-group">
                        <label>{{__('reservations.table.filter.select_venue')}}:</label>
                        <select class="hubers-select-input white medium" name="venue" id="">
                            <option value="">{{__('reservations.table.filter.select_venue')}}</option>
                            @foreach($venues as $venue)
                            <option value="{{$venue->id}}" @if($venue->id == app('request')->input('venue')) selected @endif>{{$venue->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="hubers-filter-group">
                        <label>{{__('reservations.table.filter.select_menu')}}:</label>
                        <select class="hubers-select-input white medium" name="menu" id="">
                            <option value="">{{__('reservations.table.filter.select_menu')}}</option>
                            @foreach($menus as $menu)
                            <option value="{{$menu->id}}" @if($menu->id == app('request')->input('menu')) selected @endif>{{$menu->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="hubers-filter-list-actions">
                    <button type="submit" class="hubers-btn mr-2">{{__('general.filter_btn')}}</button>
                    <a href="/reservations" class="hubers-btn inverse">{{__('general.filter_reset_btn')}}</a>
                </div>
            </div>
        </form>
        @if(count($reservations) > 0)
            <div class="table-responsive ">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th width="40">
                            <input class="main-checkbox bug-checkbox-input" type="checkbox">
                        </th>
                        <th>{{__('reservations.table.date')}}</th>
                        <th>{{__('reservations.table.venue')}}</th>
                        <th>{{__('reservations.table.description')}}</th>
                        <th>{{__('reservations.table.payment')}}</th>
                        <th>{{__('reservations.table.client')}}</th>
                        <th>{{__('reservations.table.total_services')}}</th>
                        <th>{{__('reservations.table.total_discount')}}</th>
                        <th>{{__('reservations.table.created_date')}}</th>
                        <th width="40"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reservations as $reservation)
                        <tr>
                            <td>
                                <input class="table-checkbox bug-checkbox-input" type="checkbox" value="{{$reservation->id}}">
                            </td>
                            <td>
                               {{$reservation->date}}
                            </td>
                          
                            <td>
                                <a class="hubers-link" href="{{route('venues.view',['id'=>$reservation->venue->id])}}"> {{$reservation->venue->name}} </a>
                             </td>
                            <td>
                               {{$reservation->description}}
                            </td>
                            <td>
                                {{$reservation->current_payment}}€ / {{$reservation->total_payment}}€
                            </td>
                             <td>
                                <a class="hubers-link" href="{{route('clients.view',['id'=>$reservation->client->id])}}"> {{$reservation->client->name}} </a>
                             </td>
                             <td>
                                {{$reservation->totalInvoiceAmount}}
                             </td>

                             <td>
                                {{$reservation->totalDiscountAmount}}
                             </td>
                             <td>
                                {{$reservation->created_at}}
                             </td>
                            <td>
                                <div class="bug-table-item-options">
                                    <a class="bug-table-item-option" href="{{route('reservations.view',['id'=>$reservation->id])}}">
                                        <i class="fa fa-eye"></i>
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
                    <h5 class="text-center">{{__('reservations.table.not_found_with_search')}}</h5>
                @else
                    <h5 class="text-center">{{__('reservations.table.not_found_without_search')}}</h5>
                @endif
            </div>
        @endif
    </div>
@endsection
