@extends('layouts.app')

@section('header')
    Pagesat
@endsection
@section('content')
    <div class="vms_panel">
        @if(count($payments) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Klienti</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Vlera</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Data</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            Shenime</th>
                        <th width="40" class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>
                                <a class="hubers-link" href="{{route('clients.view',['id'=>$payment->client->id])}}"> {{$payment->client->name}} </a>
                             </td>
                            <td>
                                {{$payment->value}}
                            </td>
                            <td>
                                {{$payment->date}}
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
            <h6 class="text-center">There are no reservations currently</h6>
        @endif
    </div>

@endsection
