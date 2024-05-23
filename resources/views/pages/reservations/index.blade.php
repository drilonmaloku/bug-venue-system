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
                            Date</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Venue</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Description</th>
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
                                {{--                                                <div class="d-flex px-3">--}}
                                {{--                                                    <a href="{{route('venues.view',['id'=>$reservation->id])}}">--}}
                                {{--                                                        <i class="fa fa-eye"></i>--}}
                                {{--                                                    </a>--}}
                                {{--                                                </div>--}}
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
