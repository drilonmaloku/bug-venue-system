@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Process Credit Deposit Payment') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>{{ __('Deposit Information') }}</h5>
                            <p><strong>{{ __('Deposit Number') }}:</strong> {{ $deposit->deposit_number }}</p>
                            <p><strong>{{ __('Amount') }}:</strong> {{ number_format($deposit->amount, 2) }}</p>
                            <p><strong>{{ __('Credits') }}:</strong> {{ $deposit->credits }}</p>
                        </div>
                    </div>

                    <form action="{{ route('location.credit-deposits.process-transaction', [$location, $deposit]) }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="payment_method">{{ __('Payment Method') }}</label>
                            <select class="form-control @error('payment_method') is-invalid @enderror" 
                                    id="payment_method" name="payment_method" required>
                                <option value="">{{ __('Select Payment Method') }}</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>
                                    {{ __('Cash') }}
                                </option>
                                <option value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'selected' : '' }}>
                                    {{ __('Bank Transfer') }}
                                </option>
                                <option value="credit_card" {{ old('payment_method') == 'credit_card' ? 'selected' : '' }}>
                                    {{ __('Credit Card') }}
                                </option>
                            </select>
                            @error('payment_method')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="transaction_id">{{ __('Transaction ID') }}</label>
                            <input type="text" class="form-control @error('transaction_id') is-invalid @enderror" 
                                   id="transaction_id" name="transaction_id" value="{{ old('transaction_id') }}">
                            @error('transaction_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="notes">{{ __('Notes') }}</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{ __('Process Payment') }}</button>
                            <a href="{{ route('location.credit-deposits.show', [$location, $deposit]) }}" class="btn btn-secondary">
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