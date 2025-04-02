@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ __('Credit Deposits') }}</h3>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <a href="{{ route('location.credit-deposits.create', $location) }}" class="btn btn-primary">
                            {{ __('Create New Credit Deposit') }}
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{ __('Deposit Number') }}</th>
                                    <th>{{ __('Amount') }}</th>
                                    <th>{{ __('Credits') }}</th>
                                    <th>{{ __('Status') }}</th>
                                    <th>{{ __('Due Date') }}</th>
                                    <th>{{ __('Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($deposits as $deposit)
                                    <tr>
                                        <td>{{ $deposit->deposit_number }}</td>
                                        <td>{{ number_format($deposit->amount, 2) }}</td>
                                        <td>{{ $deposit->credits }}</td>
                                        <td>
                                            @if($deposit->isCompleted())
                                                <span class="badge badge-success">{{ __('Completed') }}</span>
                                            @elseif($deposit->isOverdue())
                                                <span class="badge badge-danger">{{ __('Overdue') }}</span>
                                            @else
                                                <span class="badge badge-warning">{{ __('Pending') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $deposit->due_date ? $deposit->due_date->format('Y-m-d') : 'N/A' }}</td>
                                        <td>
                                            <a href="{{ route('location.credit-deposits.show', [$location, $deposit]) }}" 
                                               class="btn btn-sm btn-info">
                                                {{ __('View') }}
                                            </a>
                                            @if(!$deposit->isCompleted())
                                                <a href="{{ route('location.credit-deposits.process', [$location, $deposit]) }}" 
                                                   class="btn btn-sm btn-success">
                                                    {{ __('Process') }}
                                                </a>
                                            @endif
                                            <a href="{{ route('location.credit-deposits.pdf', [$location, $deposit]) }}" 
                                               class="btn btn-sm btn-secondary">
                                                {{ __('Download') }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">{{ __('No credit deposits found.') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{ $deposits->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 