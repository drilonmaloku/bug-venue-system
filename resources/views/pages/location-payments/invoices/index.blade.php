@extends('layouts.app')

@section('header')
@if($is_system_admin)
    {{__('dashboard.invoices')}} - @if(isset($location) && $location) {{ $location->name }} @else All Locations @endif
@else
    {{__('dashboard.invoices')}} - {{ $location->name }}
@endif
@endsection

@section('header-actions')
    @if($is_system_admin)
    <form action="{{ route('location-payments.invoices.generate-for-credit-deposits') }}" method="POST" class="d-inline">
            @if(isset($location))
                <a class="hubers-btn mr-0" href="{{ route('location.invoices.create', $location) }}">
                    <i class="fa fa-plus mr-2"></i> {{__('dashboard.create_invoice')}}
                </a>
            @endif
            @csrf
            <button type="submit" class="hubers-btn">
                <i class="fa fa-sync-alt mr-2"></i> {{__('dashboard.generate_invoices_for_credit_deposits')}}
            </button>
        </form>
    @endif
@endsection

@section('content')
    <div class="vms_panel">
        <form class="filter-items" action="{{ $is_system_admin ? route('location-payments.invoices.all') : route('location.invoices.index', $location) }}" method="GET">
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
                        <label>{{__('dashboard.invoice_number')}}:</label>
                        <input placeholder="{{__('dashboard.invoice_number')}}" class="bug-text-input white medium" type="text" name="invoice_number" value="{{ request('invoice_number') }}">
                    </div>
                    @if($is_system_admin)
                    <div class="hubers-filter-group">
                        <label>{{__('dashboard.locations')}}:</label>
                        <input placeholder="{{__('dashboard.locations')}}" class="bug-text-input white medium" type="text" name="location" value="{{ request('location') }}">
                    </div>
                    @endif
                    <div class="hubers-filter-group">
                        <label>{{__('dashboard.status')}}:</label>
                        <select class="hubers-select-input white medium" name="status">
                            <option value="">{{__('general.filter_title')}}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{__('dashboard.pending')}}</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>{{__('dashboard.paid')}}</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>{{__('dashboard.cancelled')}}</option>
                        </select>
                    </div>
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
                    <a href="{{ $is_system_admin ? route('location-payments.invoices.all') : route('location.invoices.index', $location) }}" class="hubers-btn inverse">{{__('general.filter_reset_btn')}}</a>
                </div>
            </div>
        </form>
        @if(count($invoices) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                        <tr>
                            <th>{{__('dashboard.invoice_number')}}</th>
                            @if($is_system_admin)
                            <th>{{__('dashboard.locations')}}</th>
                            @endif
                            <th>{{__('dashboard.credits')}}</th>
                            <th>{{__('dashboard.amount')}}</th>
                            <th>{{__('dashboard.status')}}</th>
                            <th>{{__('dashboard.due_date')}}</th>
                            <th width="120" class="text-end">{{__('dashboard.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->invoice_number }}</td>
                                @if($is_system_admin)
                                <td>{{ $invoice->location->name }}</td>
                                @endif
                                <td>{{ $invoice->credits }}</td>
                                <td>€{{ number_format($invoice->amount, 2) }}</td>
                                <td>
                                    @switch($invoice->status)
                                        @case('paid')
                                            <span class="badge badge-success">{{__('dashboard.paid')}}</span>
                                            @break
                                        @case('pending')
                                            <span class="badge badge-warning">{{__('dashboard.pending')}}</span>
                                            @break
                                        @case('cancelled')
                                            <span class="badge badge-danger">{{__('dashboard.cancelled')}}</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
                                <td>
                                    <div class="bug-table-item-options">
                                        <a class="bug-table-item-option" href="{{ route('location.invoices.show', ['location' => $is_system_admin ? $invoice->location : $location, 'invoice' => $invoice]) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a class="bug-table-item-option" href="{{ route('location.invoices.pdf', ['location' => $is_system_admin ? $invoice->location : $location, 'invoice' => $invoice]) }}">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $invoices->links() }}
        @else
            <div class="hubers-empty-tab">
                <h5 class="text-center">{{__('dashboard.no_invoices')}}</h5>
            </div>
        @endif
    </div>
@endsection 
