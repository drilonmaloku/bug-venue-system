@extends('layouts.app')

@section('header')
   Rezervimi : {{$reservations->name}}
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
                        <td>Venue</td>
                        <td>{{ $reservations->name }}</td>
                    </tr>
                    <tr>
                        <td>Decription</td>
                        <td>{{ $reservations->description }}</td>
                    </tr>
                    <tr>
                        <td>Kapaciteti</td>
                        <td>{{ $reservations->capacity}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
