@extends('layouts.app')

@section('header')
   Klienti : {{$client->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <div class="bug-table-item-options">
                    <a class="bug-table-item-option" href="{{route('clients.edit',['id'=>$client->id])}}">
                        <i class="fa fa-edit"></i>
                    </a>
                </div>
                <table>
                    <thead>
                    <tr>
                        <th colspan="2">Informatat:</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Emri</td>
                        <td>{{ $client->name }}</td>
                    </tr>
                    <tr>
                        <td>Emaili</td>
                        <td>{{ $client->email }}</td>
                    </tr>
                    <tr>
                        <td>Telefoni</td>
                        <td>{{ $client->phone_number }}</td>
                    </tr>

                    <tr>
                        <td>Telefoni Opsional</td>
                        <td>{{ $client->additional_phone_number }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="vms_panel">
        <h5>Rezervimet:</h5>
        @if(count($client->reservations) > 0)
            <div class="table-responsive ">
                <table class="bug-venue-table">
                    <thead>
                    <tr>
                        <th>Data</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Salla</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Pagesa
                        </th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Pershkrimi</th>
                        <th width="40" class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($client->reservations as $reservation)
                        <tr>
                            <td>
                                {{$reservation->date}}
                            </td>
                            <td>
                                {{$reservation->venue->name}}
                            </td>
                            <td>
                                {{$reservation->current_payment}} / {{$reservation->total_payment}}
                            </td>
                            <td>
                                {{$reservation->description}}
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
        @endif
    </div>
    <div class="vms_panel">
        <h5>Pagesat:</h5>
        @if(count($client->payments) > 0)
            <div class="table-responsive ">
                <table class="bug-venue-table">
                    <thead>
                    <tr>
                        <th class="">
                            Data</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Shuma</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Shenime</th>
                        <th width="40" class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($client->payments as $payment)
                        <tr>
                            <td>
                                {{$payment->date}}
                            </td>
                            <td>
                                {{$payment->value}}
                            </td>
                            <td>
                                {{$payment->notes}}
                            </td>

                            <td>
                                <div class="bug-table-item-options">
                                    <a class="bug-table-item-option" href="{{route('payments.view',['id'=>$payment->id])}}">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
