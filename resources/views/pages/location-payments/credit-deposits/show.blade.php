@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Credit Deposit Details') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ __('Location Information') }}</h5>
                            <p><strong>{{ __('Name') }}:</strong> {{ $location->name }}</p>
                            <p><strong>{{ __('Address') }}:</strong> {{ $location->address }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>{{ __('Deposit Information') }}</h5>
                            <p><strong>{{ __('Deposit Number') }}:</strong> {{ $deposit->deposit_number }}</p>
                            <p><strong>{{ __('Status') }}:</strong> 
                                @if($deposit->isCompleted())
                                    <span class="badge badge-success">{{ __('Completed') }}</span>
                                @elseif($deposit->isOverdue())
                                    <span class="badge badge-danger">{{ __('Overdue') }}</span>
                                @else
                                    <span class="badge badge-warning">{{ __('Pending') }}</span>
                                @endif
                            </p>
                            <p><strong>{{ __('Due Date') }}:</strong> {{ $deposit->due_date->format('Y-m-d') }}</p>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>{{ __('Amount Details') }}</h5>
                            <p><strong>{{ __('Amount') }}:</strong> {{ number_format($deposit->amount, 2) }}</p>
                            <p><strong>{{ __('Credits') }}:</strong> {{ $deposit->credits }}</p>
                            <p><strong>{{ __('Description') }}:</strong> {{ $deposit->description }}</p>
                        </div>
                        <div class="col-md-6">
                            <h5>{{ __('Actions') }}</h5>
                            @if(!$deposit->isCompleted())
                                <a href="{{ route('location.credit-deposits.process', [$location, $deposit]) }}" 
                                   class="btn btn-success mb-2">
                                    {{ __('Process Payment') }}
                                </a>
                            @endif
                            <a href="{{ route('location.credit-deposits.download', [$location, $deposit]) }}" 
                               class="btn btn-secondary mb-2">
                                {{ __('Download PDF') }}
                            </a>
                            <a href="{{ route('location.credit-deposits.index', $location) }}" 
                               class="btn btn-primary mb-2">
                                {{ __('Back to List') }}
                            </a>
                        </div>
                    </div>

                    @if($deposit->transactions->isNotEmpty())
                        <div class="row mt-4">
                            <div class="col-12">
                                <h5>{{ __('Transactions') }}</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th>{{ __('Date') }}</th>
                                                <th>{{ __('Amount') }}</th>
                                                <th>{{ __('Credits') }}</th>
                                                <th>{{ __('Payment Method') }}</th>
                                                <th>{{ __('Transaction ID') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($deposit->transactions as $transaction)
                                                <tr>
                                                    <td>{{ $transaction->created_at->format('Y-m-d H:i') }}</td>
                                                    <td>{{ number_format($transaction->amount, 2) }}</td>
                                                    <td>{{ $transaction->credits }}</td>
                                                    <td>{{ $transaction->payment_method }}</td>
                                                    <td>{{ $transaction->transaction_id }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 