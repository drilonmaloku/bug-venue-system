@extends('layouts.app')

@section('header')
{{__('dashboard.credit_deposit')}}: {{ $deposit->deposit_number }}
@endsection

@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <div class="bug-table-item-options">
                    <a class="bug-table-item-option" href="{{ route('location.credit-deposits.index', $location) }}">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                    @if($deposit->isPending())
                        <form action="{{ route('location.credit-deposits.mark-as-completed', [$location, $deposit]) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="bug-table-item-option">
                                <i class="fa fa-check"></i>
                            </button>
                        </form>
                    @endif
                </div>

                <table>
                    <thead>
                        <tr>
                            <th colspan="2">{{__('dashboard.deposit_info')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{__('dashboard.deposit_number')}}</td>
                            <td>{{ $deposit->deposit_number }}</td>
                        </tr>
                        <tr>
                            <td>{{__('dashboard.credits')}}</td>
                            <td>{{ $deposit->credits }}</td>
                        </tr>
                        <tr>
                            <td>{{__('dashboard.status')}}</td>
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
                                    @default
                                        <span class="badge badge-secondary">{{ $deposit->status }}</span>
                                @endswitch
                            </td>
                        </tr>
                        <tr>
                            <td>{{__('dashboard.due_date')}}</td>
                            <td>{{ $deposit->due_date ? $deposit->due_date->format('Y-m-d') : '-' }}</td>
                        </tr>
                        @if($deposit->completed_date)
                            <tr>
                                <td>{{__('dashboard.completed_date')}}</td>
                                <td>{{ $deposit->completed_date->format('Y-m-d') }}</td>
                            </tr>
                        @endif
                        <tr>
                            <td>{{__('dashboard.description')}}</td>
                            <td>{{ $deposit->description }}</td>
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

                @if($deposit->transactions->count() > 0)
                    <div class="mt-4">
                        <h4>{{__('dashboard.transactions')}}</h4>
                        <div class="table-responsive p-0">
                            <table class="bug-table">
                                <thead>
                                    <tr>
                                        <th>{{__('dashboard.amount')}}</th>
                                        <th>{{__('dashboard.credits')}}</th>
                                        <th>{{__('dashboard.date')}}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($deposit->transactions as $transaction)
                                        <tr>
                                            <td>€{{ number_format($transaction->amount, 2) }}</td>
                                            <td>{{ $transaction->credits }}</td>
                                            <td>{{ $transaction->created_at->format('Y-m-d') }}</td>
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