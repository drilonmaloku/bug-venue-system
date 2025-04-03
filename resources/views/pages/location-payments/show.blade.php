@extends('layouts.app')

@section('header')
    {{__('dashboard.location_payments')}}: {{ $location->name }}
@endsection

@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <div class="bug-table-item-options">
                    <a class="hubers-btn mr-2" href="{{ route('location-payments.credit-deposits.create', ['location' => $location->id]) }}">
                        <i class="fa fa-plus mr-2"></i> {{ __('Credit Deposit') }}
                    </a>
                    <a class="bug-table-item-option ml-2" href="{{ route('locations.edit', ['id' => $location->id]) }}">
                        <i class="fa fa-edit"></i>
                    </a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th colspan="2">{{__('dashboard.location_info')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{__('dashboard.name')}}</td>
                            <td>{{ $location->name }}</td>
                        </tr>
                        <tr>
                            <td>{{__('dashboard.credits')}}</td>
                            <td>{{ $location->credits }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-4">
                    <h4>{{__('dashboard.invoices')}}</h4>
                    @if(count($location->invoices) > 0)
                        <div class="table-responsive p-0">
                            <table class="bug-table">
                                <thead>
                                    <tr>
                                        <th>{{__('dashboard.invoice_number')}}</th>
                                        <th>{{__('dashboard.credits')}}</th>
                                        <th>{{__('dashboard.status')}}</th>
                                        <th>{{__('dashboard.due_date')}}</th>
                                        <th>{{__('dashboard.actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($location->invoices as $invoice)
                                        <tr>
                                            <td>{{ $invoice->invoice_number }}</td>
                                            <td>{{ $invoice->credits }}</td>
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
                                                    <a class="bug-table-item-option" href="{{ route('location.invoices.show', [$location, $invoice]) }}">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a class="bug-table-item-option" href="{{ route('location.invoices.pdf', [$location, $invoice]) }}">
                                                        <i class="fa fa-download"></i>
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
                            <h5 class="text-center">{{__('dashboard.no_invoices')}}</h5>
                        </div>
                    @endif
                </div>

                <div class="mt-4">
                    <h4>{{__('dashboard.credit_deposits')}}</h4>
                    @if(count($location->creditDeposits) > 0)
                        <div class="table-responsive p-0">
                            <table class="bug-table">
                                <thead>
                                    <tr>
                                        <th>{{__('dashboard.deposit')}}</th>
                                        <th>{{__('dashboard.credits')}}</th>
                                        <th>{{__('dashboard.status')}}</th>
                                        <th>{{__('dashboard.date')}}</th>
                                        <th>{{__('dashboard.actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($location->creditDeposits as $deposit)
                                        <tr>
                                            <td>{{ $deposit->deposit_number }}</td>
                                            <td>{{ $deposit->credits }}</td>
                                            <td>
                                                @switch($deposit->status)
                                                    @case('completed')
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
                                            <td>{{ $deposit->created_at->format('Y-m-d') }}</td>
                                            <td>
                                                <div class="bug-table-item-options">
                                                    <a class="bug-table-item-option" href="{{ route('location.credit-deposits.show', [$location, $deposit]) }}">
                                                        <i class="fa fa-eye"></i>
                                                    </a>
                                                    <a class="bug-table-item-option" href="{{ route('location.credit-deposits.pdf', [$location, $deposit]) }}">
                                                        <i class="fa fa-download"></i>
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
                            <h5 class="text-center">{{__('dashboard.no_credit_transactions')}}</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection 