@extends('layouts.app')

@section('header')
{{__('dashboard.invoices')}}
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{__('dashboard.invoices')}}</h3>
                    <div class="card-tools">
                        <a href="{{ route('location.invoices.create', $location) }}" class="btn btn-primary">
                            {{__('general.create_btn')}}
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>{{__('dashboard.invoice_number')}}</th>
                                    <th>{{__('dashboard.credits')}}</th>
                                    <th>{{__('dashboard.status')}}</th>
                                    <th>{{__('dashboard.due_date')}}</th>
                                    <th>{{__('dashboard.actions')}}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->invoice_number }}</td>
                                        <td>{{ $invoice->credits }}</td>
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
                                        <td>{{ $invoice->due_date->format('Y-m-d') }}</td>
                                        <td>
                                            <a href="{{ route('location.invoices.show', [$location, $invoice]) }}" class="btn btn-info btn-sm">
                                                {{__('general.view')}}
                                            </a>
                                            <a href="{{ route('location.invoices.pdf', [$location, $invoice]) }}" class="btn btn-secondary btn-sm">
                                                {{__('general.download')}}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center">{{__('dashboard.no_invoices')}}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    {{ $invoices->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 