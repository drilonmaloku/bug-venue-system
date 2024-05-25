@extends('layouts.app')

@section('header')
   Pagesa : {{$payment->id}}
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
                        <td>Klienti</td>
                        <td><a class="hubers-link" href="{{route('clients.view',['id'=>$payment->client->id])}}"> {{$payment->client->name}} </a></td>
                    </tr>
                    <tr>
                        <td>Rezervimi</td>
                        <td>{{ $payment->reservation->description}}</td>
                    </tr>
                    <tr>
                        <td>Vlera</td>
                        <td>{{ $payment->value }}</td>
                    </tr>
                    <tr>
                        <td>Notes</td>
                        <td>{{ $payment->notes }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
