@extends('layouts.app')

@section('header')
    {{__('dashboard.invoice_details')}}: {{ $invoice->invoice_number }}
@endsection

@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <div class="bug-table-item-options">
                    <a class="bug-table-item-option ml-2" href="{{ route('location.invoices.index', $location) }}">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                    @if($invoice->isPending() && auth()->user()->hasRole('system-admin'))
                        <form action="{{ route('location.invoices.mark-as-paid', [$location, $invoice]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="bug-table-item-option">
                                <i class="fa fa-check"></i>
                            </button>
                        </form>
                    @endif
                    <a class="bug-table-item-option ml-2" href="{{ route('location.invoices.pdf', [$location, $invoice]) }}">
                        <i class="fa fa-download"></i>
                    </a>
                </div>

                <table>
                    <thead>
                        <tr>
                            <th colspan="2">{{__('dashboard.invoice_info')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{__('dashboard.invoice_number')}}</td>
                            <td>{{ $invoice->invoice_number }}</td>
                        </tr>
                        <tr>
                            <td>{{__('dashboard.credits')}}</td>
                            <td>{{ $invoice->credits }}</td>
                        </tr>
                        <tr>
                            <td>{{__('dashboard.amount')}}</td>
                            <td>€{{ number_format($invoice->amount, 2) }}</td>
                        </tr>
                        <tr>
                            <td>{{__('dashboard.status')}}</td>
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
                        </tr>
                        <tr>
                            <td>{{__('dashboard.due_date')}}</td>
                            <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
                        </tr>
                        @if($invoice->paid_date)
                            <tr>
                                <td>{{__('dashboard.paid_date')}}</td>
                                <td>{{ $invoice->paid_date->format('Y-m-d') }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td>{{__('dashboard.description')}}</td>
                            <td>{{ $invoice->description }}</td>
                        </tr>
                    </tbody>
                </table>

                <div class="mt-4">
                    <h4>{{__('dashboard.location_info')}}</h4>
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
                </div>

                @if($invoice->payments->count() > 0)
                    <div class="mt-4">
                        <h4>{{__('dashboard.payments')}}</h4>
                        <div class="table-responsive p-0">
                            <table class="bug-table">
                                <thead>
                                    <tr>
                                        <th>{{__('dashboard.amount_paid')}}</th>
                                        <th>{{__('dashboard.payment_date')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($invoice->payments as $payment)
                                        <tr>
                                            <td>€{{ number_format($payment->amount_paid, 2) }}</td>
                                            <td>{{ $payment->payment_date->format('Y-m-d') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection 