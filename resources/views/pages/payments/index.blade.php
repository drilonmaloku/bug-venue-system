@extends('layouts.app')

@section('header')
    Pagesat
@endsection
@section('content')
    <div class="vms_panel">
        <form action="/payments" method="GET" >
            <div class="hubers-filter-options">
                <div class="hubers-filter-list-options">
                    <div class="hubers-filter-group">
                        <label>Search:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="text" name="search" value="{{ request('search') }}">
                    </div>
                    <div class="hubers-filter-group">
                        <label>Data:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="date" name="date" value="{{old('date',app('request')->input('date'))}}">
                    </div>
                </div>
                
                <div class="hubers-filter-list-actions">
                    <button type="submit" class="hubers-btn mr-2">Filtro</button>
                    <a href="/payments" class="hubers-btn inverse">Reset</a>
                </div>
            </div>
        </form>
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
            {{$payments->links()}}
        @else
            <h6 class="text-center">There are no reservations currently</h6>
        @endif
    </div>

@endsection
