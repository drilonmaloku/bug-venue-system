@extends('layouts.app')

@section('header')
{{__('dashboard.invoices')}}
@endsection

@section('content')
    <div class="vms_panel">
        @if(count($invoices) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                        <tr>
                            <th>{{__('dashboard.invoice_number')}}</th>
                            <th>{{__('dashboard.credits')}}</th>
                            <th>{{__('dashboard.amount')}}</th>
                            <th>{{__('dashboard.status')}}</th>
                            <th>{{__('dashboard.due_date')}}</th>
                            <th>{{__('dashboard.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->invoice_number }}</td>
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
            {{ $invoices->links() }}
        @else
            <div class="hubers-empty-tab">
                <h5 class="text-center">{{__('dashboard.no_invoices')}}</h5>
            </div>
        @endif
    </div>
@endsection 