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
        <div class="d-flex justify-content-between items-center">
            <h5 class="mb-0">{{__('reservations.title')}}:</h5>
            <div class="export-options mb-2" onclick="exportOptions.export('/reservations/export','reservations-export.xlsx','clientReservationsTable')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
        </div>

        @if(count($client->reservations) > 0)
            <div class="table-responsive">
                <table class="bug-table" id="clientReservationsTable">
                    <thead>
                    <tr>
                        <th width="40">
                            <input class="main-checkbox bug-checkbox-input" type="checkbox">
                        </th>
                        <th>{{__('clients.table.date')}}</th>
                        <th>
                            {{__('clients.table.venue')}}</th>
                        <th>
                            {{__('clients.table.payment')}}    
                        </th>
                        <th>
                            {{__('clients.table.description')}}   </th>
                        <th width="40">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($client->reservations as $reservation)
                        <tr>
                            <td>
                                <input class="table-checkbox bug-checkbox-input" type="checkbox" value="{{$reservation->id}}">
                            </td>
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
        <div class="d-flex justify-content-between items-center">
            <h5>{{__('payments.main.title')}} </h5>
            <div class="export-options mb-2" onclick="exportOptions.export('/payments/export','payments-export.xlsx','clientPaymentsTable')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
            </div>
        </div>

        @if(count($client->payments) > 0)
            <div class="table-responsive" id="clientPaymentsTable">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th width="40">
                            <input class="main-checkbox bug-checkbox-input" type="checkbox">
                        </th>
                        <th class="">
                            {{__('payments.table.date')}}</th>
                        <th>
                            {{__('expenses.table.amount')}}</th>
                        <th>
                            {{__('clients.table.notes')}}</th>
                        <th width="40">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($client->payments as $payment)
                        <tr>
                            <td>
                                <input class="table-checkbox bug-checkbox-input" type="checkbox" value="{{$reservation->id}}">
                            </td>
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

