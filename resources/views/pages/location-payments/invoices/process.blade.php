@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Process Payment') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>{{ __('Invoice Information') }}</h5>
                            <p><strong>{{ __('Invoice Number') }}:</strong> {{ $invoice->invoice_number }}</p>
                            <p><strong>{{ __('Credits') }}:</strong> {{ $invoice->credits }}</p>
                        </div>
                    </div>

                    <form action="{{ route('location.invoices.process.store', [$location, $invoice]) }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="amount_paid">{{ __('Amount Paid') }}</label>
                            <input type="number" step="0.01" class="form-control @error('amount_paid') is-invalid @enderror" 
                                   id="amount_paid" name="amount_paid" value="{{ old('amount_paid') }}" required>
                            @error('amount_paid')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="payment_date">{{ __('Payment Date') }}</label>
                            <input type="date" class="form-control @error('payment_date') is-invalid @enderror" 
                                   id="payment_date" name="payment_date" value="{{ old('payment_date', date('Y-m-d')) }}" required>
                            @error('payment_date')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ __('Process Payment') }}</button>
                            <a href="{{ route('location.invoices.show', [$location, $invoice]) }}" 
                               class="btn btn-secondary">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 