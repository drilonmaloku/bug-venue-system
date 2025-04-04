@extends('layouts.app')

@section('header')
{{__('dashboard.process_credit_deposit')}}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{__('dashboard.process_credit_deposit')}}</h3>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <h5>{{__('dashboard.deposit_info')}}</h5>
                            <table class="table">
                                <tr>
                                    <th>{{__('dashboard.deposit_number')}}</th>
                                    <td>{{ $deposit->deposit_number }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('dashboard.credits')}}</th>
                                    <td>{{ $deposit->credits }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('dashboard.amount')}}</th>
                                    <td>€{{ number_format($deposit->amount, 2) }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('dashboard.status')}}</th>
                                    <td>
                                        @switch($deposit->status)
                                            @case('completed')
                                                <span class="badge badge-success">{{__('dashboard.completed')}}</span>
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
                                    <td>{{ $deposit->due_date->format('d/m/Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <form action="{{ route('location.credit-deposits.process.store', [$location, $deposit]) }}" method="POST">
                        @csrf
                        
                        <div class="form-group">
                            <label for="transaction_id">{{__('dashboard.transaction_id')}}</label>
                            <input type="text" class="form-control @error('transaction_id') is-invalid @enderror" 
                                   id="transaction_id" name="transaction_id" value="{{ old('transaction_id') }}">
                            @error('transaction_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="notes">{{__('dashboard.notes')}}</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" name="notes" rows="3">{{ old('notes') }}</textarea>
                            @error('notes')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">{{__('dashboard.process_deposit')}}</button>
                            <a href="{{ route('location.credit-deposits.show', [$location, $deposit]) }}" class="btn btn-secondary">
                                {{__('general.back_btn')}}
                            </a>
                        </div>
                    </form>

                    @if($deposit->isPending() && auth()->user()->hasRole('system-admin'))
                        <form action="{{ route('location.credit-deposits.mark-as-completed', [$location, $deposit]) }}" method="POST" class="d-inline mt-3">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-check"></i> {{__('dashboard.mark_as_completed')}}
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 