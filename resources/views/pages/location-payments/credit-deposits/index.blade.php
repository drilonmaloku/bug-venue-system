@extends('layouts.app')

@section('header')
@if($is_system_admin)
    {{__('dashboard.credit_deposits')}} - {{ $location ? $location->name : 'All Locations' }}
@else
    {{__('dashboard.credit_deposits')}} - {{ $location->name }}
@endif
@endsection

@section('content')
    <div class="vms_panel">
        <form class="filter-items" action="{{ $is_system_admin ? route('location-payments.credit-deposits.all') : route('location.credit-deposits.index', $location) }}" method="GET">
            <div class="filter-options">
                <div class="huber-filter-btn @if ($is_on_search) active @endif">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-filter vue-feather__content"><polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon></svg>
                    <p>{{__('general.filter_btn')}}</p>
                    <span class="huber-filter-btn-arrow">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6 9L12 15L18 9" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>
            </div>
            <div class="hubers-filter-options @if ($is_on_search) active @endif">
                <div class="hubers-filter-list-options">
                    <div class="hubers-filter-group">
                        <label>{{__('dashboard.deposit_number')}}:</label>
                        <input placeholder="{{__('dashboard.deposit_number')}}" class="bug-text-input white medium" type="text" name="deposit_number" value="{{ request('deposit_number') }}">
                    </div>
                    @if($is_system_admin && request()->route()->getName() === 'location-payments.credit-deposits.all')
                    <div class="hubers-filter-group">
                        <label>{{__('dashboard.locations')}}:</label>
                        <input placeholder="{{__('dashboard.locations')}}" class="bug-text-input white medium" type="text" name="location" value="{{ request('location') }}">
                    </div>
                    @endif
                   
                    <div class="hubers-filter-group">
                        <label>{{__('dashboard.due_date')}} {{__('general.from')}}:</label>
                        <input class="bug-text-input white medium" type="date" name="due_date_from" value="{{ request('due_date_from') }}">
                    </div>
                    <div class="hubers-filter-group">
                        <label>{{__('dashboard.due_date')}} {{__('general.to')}}:</label>
                        <input class="bug-text-input white medium" type="date" name="due_date_to" value="{{ request('due_date_to') }}">
                    </div>
                </div>
                <div class="hubers-filter-list-actions">
                    <button type="submit" class="hubers-btn mr-2">{{__('general.filter_btn')}}</button>
                    <a href="{{ $is_system_admin ? route('location-payments.credit-deposits.all') : route('location.credit-deposits.index', $location) }}" class="hubers-btn inverse">{{__('general.filter_reset_btn')}}</a>
                </div>
            </div>
        </form>
        @if(count($deposits) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                        <tr>
                            <th>{{__('dashboard.deposit_number')}}</th>
                            @if($is_system_admin)
                            <th>{{__('dashboard.locations')}}</th>
                            @endif
                            <th>{{__('dashboard.credits')}}</th>
                            <th>{{__('dashboard.amount')}}</th>
                            <th>{{__('dashboard.due_date')}}</th>
                            <th width="120" class="text-end">{{__('dashboard.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deposits as $deposit)
                            <tr>
                                <td>{{ $deposit->deposit_number }}</td>
                                @if($is_system_admin)
                                <td>{{ $deposit->location->name }}</td>
                                @endif
                                <td>{{ $deposit->credits }}</td>
                                <td>€{{ number_format($deposit->amount, 2) }}</td>
                                <td>{{ $deposit->due_date ? $deposit->due_date->format('Y-m-d') : '-' }}</td>
                                <td>
                                    <div class="bug-table-item-options">
                                        <a class="bug-table-item-option" href="{{ route('location.credit-deposits.show', [$is_system_admin ? $deposit->location : $location, $deposit]) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $deposits->links() }}
        @else
            <div class="hubers-empty-tab">
                <h5 class="text-center">{{__('dashboard.no_credit_deposits')}}</h5>
            </div>
        @endif
    </div>
@endsection 