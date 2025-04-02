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
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file-text">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                                <polyline points="10 9 9 9 8 9"></polyline>
                                            </svg>
                                        </a>
                                       
                                        <a class="bug-table-item-option" href="{{ route('location.credit-deposits.create', ['location' => $location]) }}">
                                            <i class="fa fa-plus"></i>
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