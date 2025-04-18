@extends('layouts.app')
@section('header')
    {{__('dashboard.title')}}
@endsection
@section('content')
    <div class="vms_panel">
        @foreach($reservations as $reservation)
            <strong>Data: {{$reservation->date}} <br>
            Te ftuarit: {{$reservation->number_of_guests}} <br>
            Menuja:</strong> {!! $reservation->menu_contents !!}
            <hr>
        @endforeach
    </div>

@endsection
