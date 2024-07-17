@extends('layouts.app')

@section('header')
    Salla: {{$venue->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                @if(count($venue->reservations) == 0)
                    <form action="{{ route('venues.destroy', $venue->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm ms-auto mb-0" type="submit"><i class="fa fa-trash"></i> Fshij</button>
                    </form>
                @endif
                <table>
                    <thead>
                    <tr>
                        <th colspan="2">Informacioni:</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>EMRI</td>
                        <td>{{ $venue->name }}</td>
                    </tr>
                    <tr>
                        <td>Përshkrimi</td>
                        <td>{{ $venue->description }} </td>
                    </tr>
                    <tr>
                        <td>Kapaciteti</td>
                        <td>{{ $venue->capacity }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="vms_panel">
        <h5>Rezervimet:</h5>
        @if(count($venue->reservations) > 0)
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
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Payment
                        </th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Klienti
                        </th>
                        <th width="40" class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($venue->reservations as $reservation)
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
                                <div class="d-flex px-3">
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
                <h5 class="text-center">Nuk ka rezervime momentalisht</h5>
            </div>
        @endif
    </div>
@endsection
