@extends('layouts.app')
@section('header')
    Rezervimet
@endsection
@section('header-actions')
    <a class="hubers-btn" href="{{route('reservations.create')}}">Krijo</a>
@endsection
@section('content')
    <div class="vms_panel">
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
                                {{$reservation->venue->name}}
                            </td>
                            <td>
                               {{$reservation->description}}
                            </td>
                            <td>
                                {{$reservation->current_payment}} $
                             </td>
                            <td>
                                {{$reservation->client->name}}
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
