@extends('layouts.app')

@section('header')
{{__('payment.title')}}
@endsection
@section('content')
    <div class="vms_panel">
        <form class="filter-items" action="/payments" method="GET" >
            <div class="filter-options">
                <div class="huber-filter-btn  @if ($is_on_search) active @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter vue-feather__content"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    <p>{{__('general.filter_btn')}}</p>
                    <span class="huber-filter-btn-arrow">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L12 15L18 9" stroke="black" stroke-width="2" stroke-linecap="round"
                                  stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
                <div class="export-options" onclick="exportOptions.export('/payments/export')">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>
                </div>
            </div>
            <div class="hubers-filter-options @if ($is_on_search) active @endif">
                <div class="hubers-filter-list-options">
                    <div class="hubers-filter-group">
                        <label>{{__('payment.forms.search')}}:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="text" name="search" value="{{ request('search') }}">
                    </div>
                    <div class="hubers-filter-group">
                        <label>{{__('payment.table.date')}}:</label>
                        <input placeholder="Search" class="hubers-text-input white medium" type="date" name="date" value="{{old('date',app('request')->input('date'))}}">
                    </div>
                </div>
                
                <div class="hubers-filter-list-actions">
                    <button type="submit" class="hubers-btn mr-2">{{__('general.filter_btn')}}</button>
                    <a href="/payments" class="hubers-btn inverse">{{__('general.filter_reset_btn')}}</a>
                </div>
            </div>
        </form>
        @if(count($payments) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th width="40">
                            <input class="main-checkbox bug-checkbox-input" type="checkbox">
                        </th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            {{__('payment.table.client')}}</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            {{__('payment.table.value')}}</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            {{__('payment.table.date')}}</th>
                        <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            {{__('payment.table.description')}}</th>
                        <th width="40" class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($payments as $payment)
                        <tr>
                            <td>
                                <input class="table-checkbox bug-checkbox-input" type="checkbox" value="{{$payment->id}}">
                            </td>
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
                                    <a class="bug-table-item-option" href="{{route('payments.edit',['id'=>$payment->id])}}">
                                        <i class="fa fa-edit"></i>
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
            <div class="hubers-empty-tab">
                @if ($is_on_search)
                    <h5 class="text-center">{{__('payment.table.not_found_with_search')}}</h5>
                @else
                    <h5 class="text-center">{{__('payment.table.not_found_without_search')}}</h5>
                @endif
            </div>
        @endif
    </div>

@endsection
