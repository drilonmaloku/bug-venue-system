@extends('layouts.app')

@section('header')
   Rezervimi : {{$reservation->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <div class="bug-table-item-options">
                <table>
                    <thead>
                    <tr>
                        <th colspan="2">Informatat:</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Data</td>
                            <td>{{ $reservation->date }}</td>
                        </tr>
                        <tr>
                            <td>Salla</td>
                            <td>{{ $reservation->venue->name }}</td>
                        </tr>
                        <tr>
                            <td>Numri i te ftuarve</td>
                            <td>{{ $reservation->number_of_guests}}</td>
                        </tr>
                        <tr>
                            <td>Klienti</td>
                            <td>{{ $reservation->client->name }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
