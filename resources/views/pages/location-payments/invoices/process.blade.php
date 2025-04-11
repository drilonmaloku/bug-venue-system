@extends('layouts.app')

@section('header')
{{__('dashboard.process_payment')}}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{__('dashboard.process_payment')}}</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>{{__('dashboard.invoice_info')}}</h5>
                            <table class="table">
                                <tr>
                                    <th>{{__('dashboard.invoice_number')}}</th>
                                    <td>{{ $invoice->invoice_number }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('dashboard.credits')}}</th>
                                    <td>{{ $invoice->credits }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('dashboard.amount')}}</th>
                                    <td>€{{ number_format($invoice->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('dashboard.status')}}</th>
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
                                    <th>{{__('dashboard.due_date')}}</th>
                                    <td>{{ $invoice->due_date->format('d/m/Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <form action="{{ route('location.invoices.process.store', [$location, $invoice]) }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="amount_paid">{{__('dashboard.amount_paid')}}</label>
                            <input type="number" step="0.01" class="form-control @error('amount_paid') is-invalid @enderror" 
                                   id="amount_paid" name="amount_paid" value="{{ old('amount_paid') }}" required>
                            @error('amount_paid')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="payment_date">{{__('dashboard.payment_date')}}</label>
                            <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                   id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                            @error('payment_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{__('dashboard.process_payment')}}</button>
                            <a href="{{ route('location.invoices.show', [$location, $invoice]) }}" class="btn btn-secondary">
                                {{__('general.back_btn')}}
                            </a>
                        </div>
                    </form>

                    @if($invoice->isPending() && auth()->user()->hasRole('system-admin'))
                        <form action="{{ route('location.invoices.mark-as-paid', [$location, $invoice]) }}" method="POST" class="d-inline mt-3">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> {{__('dashboard.mark_as_paid')}}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 