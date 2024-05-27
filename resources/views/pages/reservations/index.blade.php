@extends('layouts.app')
@section('header')
    Rezervimet
@endsection
@section('header-actions')
    <a class="hubers-btn" href="{{route('reservations.create')}}">Krijo</a>
@endsection
@section('content')
    <div class="vms_panel">
        <form action="/reservations" method="GET" >
            <div class="hubers-filter-options">
                <div class="hubers-filter-list-options">
                    <div class="hubers-filter-group">
                        <label>Search:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="text" name="search" value="{{ request('search') }}">
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
                <table class="bug-venue-table">
                    <thead>
                    <tr>
                        <th class="">
                            Data</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Salla</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Pershkrimi</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Pagesa
                        </th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Klienti
                        </th>
                        <th width="40" class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($reservations as $reservation)
                        <tr>
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
                                {{$reservation->current_payment}} $
                             </td>
                       
                             <td>
                                <a class="hubers-link" href="{{route('clients.view',['id'=>$reservation->client->id])}}"> {{$reservation->client->name}} </a>
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
            <h6 class="text-center">There are no reservations currently</h6>
        @endif
    </div>

@endsection
