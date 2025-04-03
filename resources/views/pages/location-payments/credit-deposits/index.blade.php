@extends('layouts.app')

@section('header')
@if($is_system_admin)
    {{__('dashboard.credit_deposits')}} - All Locations
@else
    {{__('dashboard.credit_deposits')}} - {{ $location->name }}
@endif
@endsection

@section('content')
    <div class="vms_panel">
        @if(count($deposits) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                        <tr>
                            <th>{{__('dashboard.deposit_number')}}</th>
                            @if($is_system_admin)
                            <th>{{__('dashboard.locations')}}</th>
                            @endif
                            <th>{{__('dashboard.credits')}}</th>
                            <th>{{__('dashboard.amount')}}</th>
                            <th>{{__('dashboard.status')}}</th>
                            <th>{{__('dashboard.due_date')}}</th>
                            <th>{{__('dashboard.actions')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($deposits as $deposit)
                            <tr>
                                <td>{{ $deposit->deposit_number }}</td>
                                @if($is_system_admin)
                                <td>{{ $deposit->location->name }}</td>
                                @endif
                                <td>{{ $deposit->credits }}</td>
                                <td>€{{ number_format($deposit->amount, 2) }}</td>
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
                                <td>{{ $deposit->due_date ? $deposit->due_date->format('Y-m-d') : '-' }}</td>
                                <td>
                                    <div class="bug-table-item-options">
                                        <a class="bug-table-item-option" href="{{ route('location.credit-deposits.show', [$is_system_admin ? $deposit->location : $location, $deposit]) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a class="bug-table-item-option" href="{{ route('location.credit-deposits.pdf', [$is_system_admin ? $deposit->location : $location, $deposit]) }}">
                                            <i class="fa fa-download"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $deposits->links() }}
        @else
            <div class="hubers-empty-tab">
                <h5 class="text-center">{{__('dashboard.no_credit_deposits')}}</h5>
            </div>
        @endif
    </div>
@endsection 