@extends('layouts.app')

@section('header')
    {{__('dashboard.payments')}} - {{ $location->name }}
@endsection

@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <div class="mt-4">
                    <h4>{{__('dashboard.invoice_payments')}}</h4>
                    @if(count($location->invoices->where('status', 'paid')) > 0)
                        <div class="table-responsive p-0">
                            <table class="bug-table">
                                <thead>
                                    <tr>
                                        <th>{{__('dashboard.invoice_number')}}</th>
                                        <th>{{__('dashboard.amount')}}</th>
                                        <th>{{__('dashboard.payment_date')}}</th>
                                        <th width="120" class="text-end">{{__('dashboard.actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($location->invoices->where('status', 'paid') as $invoice)
                                        @foreach($invoice->payments as $payment)
                                            <tr>
                                                <td>{{ $invoice->invoice_number }}</td>
                                                <td>€{{ number_format($payment->amount_paid, 2) }}</td>
                                                <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
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
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="hubers-empty-tab">
                            <h5 class="text-center">{{__('dashboard.no_invoice_payments')}}</h5>
                        </div>
                    @endif
                </div>

                <div class="mt-4">
                    <h4>{{__('dashboard.credit_deposits')}}</h4>
                    @if(count($location->creditDeposits->where('status', 'completed')) > 0)
                        <div class="table-responsive p-0">
                            <table class="bug-table">
                                <thead>
                                    <tr>
                                        <th>{{__('dashboard.deposit_number')}}</th>
                                        <th>{{__('dashboard.amount')}}</th>
                                        <th>{{__('dashboard.credits')}}</th>
                                        <th>{{__('dashboard.completed_date')}}</th>
                                        <th width="120" class="text-end">{{__('dashboard.actions')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($location->creditDeposits->where('status', 'completed') as $deposit)
                                        <tr>
                                            <td>{{ $deposit->deposit_number }}</td>
                                            <td>€{{ number_format($deposit->amount, 2) }}</td>
                                            <td>{{ $deposit->credits }}</td>
                                            <td>{{ $deposit->completed_date->format('Y-m-d') }}</td>
                                            <td>
                                                <div class="bug-table-item-options">
                                                    <a class="bug-table-item-option" href="{{ route('location.credit-deposits.show', [$location, $deposit]) }}">
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
                            <h5 class="text-center">{{__('dashboard.no_credit_deposits')}}</h5>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection