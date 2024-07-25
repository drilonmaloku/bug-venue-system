@extends('layouts.app')
@section('header')
    Rezervimet
@endsection
@section('header-actions')
@if(count($menus) > 0 && count($venues) > 0)
<a class="hubers-btn" href="{{route('reservations.create')}}">Krijo</a>
@endif
@endsection
@section('content')
    <div class="vms_panel">
        <form class="filter-items" action="/reservations" method="GET" >
            <div class="filter-options">
                <div class="huber-filter-btn  @if ($is_on_search) active @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter vue-feather__content"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    <p>Filtro</p>
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
                        <label>Search:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="text" name="search" value="{{ request('search') }}">
                    </div>
                    <div class="hubers-filter-group">
                        <label>Data:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="date" name="date" value="{{old('date',app('request')->input('date'))}}">
                    </div>

                    <div class="hubers-filter-group">
                        <label>Data e krijimit:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="date" name="created_at" value="{{ old('created_at', app('request')->input('created_at')) }}">
                    </div>


                    <div class="hubers-filter-group">
                        <label>Selekto Sallën:</label>
                        <select class="hubers-select-input white medium" name="venue" id="">
                            <option value="">Selekto Sallën</option>
                            @foreach($venues as $venue)
                            <option value="{{$venue->id}}" @if($venue->id == app('request')->input('venue')) selected @endif>{{$venue->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="hubers-filter-group">
                        <label>Selekto Menunë:</label>
                        <select class="hubers-select-input white medium" name="menu" id="">
                            <option value="">Selekto Menunë</option>
                            @foreach($menus as $menu)
                            <option value="{{$menu->id}}" @if($menu->id == app('request')->input('menu')) selected @endif>{{$menu->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="hubers-filter-list-actions">
                    <button type="submit" class="hubers-btn mr-2">Filtro</button>
                    <a href="/reservations" class="hubers-btn inverse">Reset</a>
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
                        <th>Data</th>
                        <th>Salla</th>
                        <th>Pershkrimi</th>
                        <th>Pagesa</th>
                        <th>Klienti</th>
                        <th>Sherbimi Totali</th>
                        <th>Zbritja Totali</th>
                        <th>Data e krijimit te Rezervimit</th>
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
                    <h5 class="text-center">Nuk ka rezervime sipas search</h5>
                @else
                    <h5 class="text-center">Nuk ka rezervime momentalisht</h5>
                @endif
            </div>
        @endif
    </div>

@endsection
