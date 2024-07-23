@extends('layouts.app')

@section('header')
   {{__('clients.view.title')}} : {{$client->name}}
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
                        <th colspan="2">{{__('clients.table.information')}}:</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{__('clients.table.name')}}</td>
                        <td>{{ $client->name }}</td>
                    </tr>
                    <tr>
                        <td>{{__('clients.table.email')}}</td>
                        <td>{{ $client->email }}</td>
                    </tr>
                    <tr>
                        <td>{{__('clients.table.phone_number')}}</td>
                        <td>{{ $client->phone_number }}</td>
                    </tr>

                    <tr>
                        <td>{{__('clients.table.additional_phone_number')}}</td>
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
                        <th>{{__('clients.table.date')}}</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            {{__('clients.table.venue')}}</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            {{__('clients.table.payment')}}    
                        </th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            {{__('clients.table.description')}}   </th>
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

            @else
            <div class="hubers-empty-tab">
                <h5 class="text-center">{{__('clients.view.no_reservations_for_client')}}</h5>
            </div>
        @endif
    </div>
    <div class="vms_panel">
        <h5>{{__('payments.main.title')}} </h5>
        @if(count($client->payments) > 0)
            <div class="table-responsive ">
                <table class="bug-venue-table">
                    <thead>
                    <tr>
                        <th class="">
                            {{__('payments.table.date')}}</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            {{__('expenses.table.amount')}}</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            {{__('clients.table.notes')}}</th>
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
            @else
            <div class="hubers-empty-tab">
                <h5 class="text-center">{{__('clients.view.no_payments_for_client')}}</h5>
            </div>
        @endif
    </div>
@endsection

