@extends('layouts.app')

@section('header')
    {{ __('Location Payments') }} - {{ $location->name }}
@endsection

@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <div class="bug-table-item-options mb-4">
                    <a class="hubers-btn mr-2" href="{{ route('location.invoices.create', ['location' => $location]) }}">
                        <i class="fa fa-plus mr-2"></i> {{ __('New Invoice') }}
                    </a>
                    <a class="hubers-btn mr-2" href="{{ route('location.credit-deposits.create', ['location' => $location]) }}">
                        <i class="fa fa-plus mr-2"></i> {{ __('New Credit Deposit') }}
                    </a>
                    <a class="hubers-btn mr-2" href="{{ route('location-payments.index') }}">
                        <i class="fa fa-arrow-left mr-2"></i> {{ __('Back to List') }}
                    </a>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Location Information') }}</h5>
                    </div>
                    <div class="card-body">
                        <table class="bug-table">
                            <tr>
                                <th>{{ __('Owner') }}</th>
                                <td>{{ $location->user->first_name }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Credits') }}</th>
                                <td>{{ $location->credits }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Pending Invoices') }}</th>
                                <td>{{ $location->invoices->where('status', 'pending')->count() }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Paid Invoices') }}</th>
                                <td>{{ $location->invoices->where('status', 'paid')->count() }}</td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Recent Invoices') }}</h5>
                    </div>
                    <div class="card-body">
                        @if($location->invoices->count() > 0)
                            <div class="table-responsive">
                                <table class="bug-table">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Invoice Number') }}</th>
                                            <th>{{ __('Credits') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th>{{ __('Due Date') }}</th>
                                            <th>{{ __('Actions') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($location->invoices->take(5) as $invoice)
                                            <tr>
                                                <td>{{ $invoice->invoice_number }}</td>
                                                <td>{{ $invoice->credits }}</td>
                                                <td>
                                                    <span class="badge badge-{{ $invoice->status === 'paid' ? 'success' : ($invoice->status === 'pending' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($invoice->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                                                <td>
                                                    <div class="bug-table-item-options">
                                                        <a class="bug-table-item-option" href="{{ route('location.invoices.show', ['location' => $location, 'invoice' => $invoice]) }}">
                                                            <i class="fa fa-eye"></i>
                                                        </a>
                                                        @if($invoice->status === 'pending')
                                                            <a class="bug-table-item-option" href="{{ route('location.invoices.process', ['location' => $location, 'invoice' => $invoice]) }}">
                                                                <i class="fa fa-money"></i>
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="text-center">
                                <p>{{ __('No invoices found') }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection 