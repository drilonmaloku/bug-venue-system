@extends('layouts.app')

@section('header')
    {{ __('Location Payments') }}
@endsection

@section('content')
    <div class="vms_panel">
        @if(count($locations) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                        <tr>
                            <th>{{ __('Location') }}</th>
                            <th>{{ __('Owner') }}</th>
                            <th>{{ __('Credits') }}</th>
                            <th>{{ __('Pending Invoices') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($locations as $location)
                            <tr>
                                <td>{{ $location->name }}</td>
                                <td>{{ $location->user->first_name }}</td>
                                <td>{{ $location->credits }}</td>
                                <td>{{ $location->invoices->where('status', 'pending')->count() }}</td>
                                <td>
                                    <div class="bug-table-item-options">
                                        <a class="bug-table-item-option" href="{{ route('location-payments.show', ['location' => $location]) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a class="bug-table-item-option" href="{{ route('location.invoices.index', ['location' => $location]) }}">
                                            <i class="fa fa-file-text-o"></i>
                                        </a>
                                        <a class="bug-table-item-option" href="{{ route('location.credit-deposits.index', ['location' => $location]) }}">
                                            <i class="fa fa-money"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="hubers-empty-tab">
                <h5 class="text-center">{{ __('No locations found') }}</h5>
            </div>
        @endif
    </div>
@endsection 