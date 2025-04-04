@extends('layouts.app')

@section('header')
@if($is_system_admin)
    {{__('dashboard.credit_transactions')}} - All Locations
@else
    {{__('dashboard.credit_transactions')}} - {{ $location->name }}
@endif
@endsection

@section('content')
    <div class="vms_panel">
        <form class="filter-items" action="{{ $is_system_admin ? route('location-payments.credit-transactions.all') : route('credit-transactions.index', $location) }}" method="GET">
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
                    @if($is_system_admin)
                    <div class="hubers-filter-group">
                        <label>{{__('dashboard.locations')}}:</label>
                        <input placeholder="{{__('dashboard.locations')}}" class="bug-text-input white medium" type="text" name="location" value="{{ request('location') }}">
                    </div>
                    @endif
                    <div class="hubers-filter-group">
                        <label>{{__('dashboard.payment_method')}}:</label>
                        <select class="hubers-select-input white medium" name="payment_method">
                            <option value="">{{__('general.filter_title')}}</option>
                            <option value="cash" {{ request('payment_method') == 'cash' ? 'selected' : '' }}>{{__('payment.method.cash')}}</option>
                            <option value="gift" {{ request('payment_method') == 'gift' ? 'selected' : '' }}>{{__('payment.method.gift')}}</option>
                        </select>
                    </div>
                    <div class="hubers-filter-group">
                        <label>{{__('dashboard.date')}} {{__('general.from')}}:</label>
                        <input class="bug-text-input white medium" type="date" name="date_from" value="{{ request('date_from') }}">
                    </div>
                    <div class="hubers-filter-group">
                        <label>{{__('dashboard.date')}} {{__('general.to')}}:</label>
                        <input class="bug-text-input white medium" type="date" name="date_to" value="{{ request('date_to') }}">
                    </div>
                </div>
                <div class="hubers-filter-list-actions">
                    <button type="submit" class="hubers-btn mr-2">{{__('general.filter_btn')}}</button>
                    <a href="{{ $is_system_admin ? route('location-payments.credit-transactions.all') : route('credit-transactions.index', $location) }}" class="hubers-btn inverse">{{__('general.filter_reset_btn')}}</a>
                </div>
            </div>
        </form>

        @if(count($transactions) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                        <tr>
                            @if($is_system_admin)
                            <th>{{__('dashboard.locations')}}</th>
                            @endif
                            <th>{{__('dashboard.credits')}}</th>
                            <th>{{__('dashboard.amount')}}</th>
                            <th>{{__('dashboard.payment_method')}}</th>
                            <th>{{__('dashboard.notes')}}</th>
                            <th>{{__('dashboard.date')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($transactions as $transaction)
                            <tr>
                                @if($is_system_admin)
                                <td>{{ $transaction->location->name }}</td>
                                @endif
                                <td>{{ $transaction->credits }}</td>
                                <td>€{{ number_format($transaction->amount, 2) }}</td>
                                <td>{{ $transaction->payment_method }}</td>
                                <td>{{ $transaction->notes }}</td>
                                <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $transactions->links() }}
        @else
            <div class="hubers-empty-tab">
                <h5 class="text-center">{{__('dashboard.no_credit_transactions')}}</h5>
            </div>
        @endif
    </div>
@endsection 