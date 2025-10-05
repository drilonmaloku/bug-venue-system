@extends('layouts.app')
@section('header')
    {{__('dashboard.title')}}
@endsection
@section('content')
    <div class="vms_panel">
        @foreach($reservations as $reservation)
            <div class="vms-panel-bordered">
                <strong>Data:</strong> {{$reservation->date}} <br>
                <strong>Salla:</strong> {{$reservation->venue->name}} <br>
                <strong>Te ftuarit:</strong> {{$reservation->number_of_guests}} <br>
                <strong> Menuja:</strong> {!! $reservation->menu_contents !!}
            </div>

        @endforeach
    </div>

@endsection
