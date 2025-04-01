@extends('layouts.app')

@section('header')
{{__('dashboard.invoice_details')}}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{__('dashboard.invoice_details')}}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{__('dashboard.invoice_info')}}</h5>
                            <table class="table">
                                <tr>
                                    <th>{{__('dashboard.invoice_number')}}</th>
                                    <td>{{ $invoice->invoice_number }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('dashboard.amount')}}</th>
                                    <td>{{ number_format($invoice->amount, 2) }} {{__('general.currency')}}</td>
                                </tr>
                                <tr>
                                    <th>{{__('dashboard.credits')}}</th>
                                    <td>{{ $invoice->credits }}</td>
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
                                @if($invoice->paid_date)
                                <tr>
                                    <th>{{__('dashboard.paid_date')}}</th>
                                    <td>{{ $invoice->paid_date->format('d/m/Y') }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th>{{__('dashboard.description')}}</th>
                                    <td>{{ $invoice->description }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>{{__('dashboard.location_info')}}</h5>
                            <table class="table">
                                <tr>
                                    <th>{{__('dashboard.name')}}</th>
                                    <td>{{ $location->name }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('dashboard.address')}}</th>
                                    <td>{{ $location->address }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('dashboard.phone')}}</th>
                                    <td>{{ $location->phone }}</td>
                                </tr>
                                <tr>
                                    <th>{{__('dashboard.email')}}</th>
                                    <td>{{ $location->email }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12">
                            @if($invoice->isPending())
                                    <button type="submit" class="btn btn-success">
                                        <i class="fas fa-check"></i> {{__('dashboard.mark_as_paid')}}
                                    </button>
                                
                            @endif
                            <a href="{{ route('location.invoices.pdf', [$location, $invoice]) }}" class="btn btn-primary">
                                <i class="fas fa-file-pdf"></i> {{__('dashboard.download_pdf')}}
                            </a>
                            <a href="{{ route('location.invoices.index', $location) }}" class="btn btn-secondary">
                                {{__('general.back_btn')}}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 