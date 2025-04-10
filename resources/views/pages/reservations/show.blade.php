@extends('layouts.app')

@section('header')
{{__('reservations.view.title')}} : {{ $reservation->name }}
@endsection
@section('content')

    @include('pages.reservations.partials.details',['reservation' => $reservation])
    @include('pages.reservations.partials.payments',['reservation' => $reservation])
    @include('pages.reservations.partials.invoices',['reservation' => $reservation])
    @include('pages.reservations.partials.discounts',['reservation' => $reservation])
    @include('pages.reservations.partials.planning',['reservation' => $reservation])
    @include('pages.reservations.partials.priceTrackings',['reservation' => $reservation])
    @include('pages.reservations.partials.comments',['reservation' => $reservation])
    @include('pages.reservations.partials.staff',['reservation' => $reservation])
    @include('pages.reservations.partials.notes',['reservation' => $reservation])
    @include('pages.reservations.partials.reminders',['reservation' => $reservation])

@endsection

<style>
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        resize: vertical;
        min-height: 100px;
    }

    .comment {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
    }

    .comment p {
        margin: 0;
    }
</style>
