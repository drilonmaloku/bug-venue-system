@extends('layouts.app')

@section('header')
{{__('payment.title.single')}} : {{$payment->id}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <form action="{{ route('payments.destroy',$payment->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm ms-auto mb-0" type="submit"><i class="fa fa-trash"></i> {{__('payment.forms.delete')}}</button>
                </form>
                <div class="bug-table-item-options">
                <table>
                    <thead>
                    <tr>
                        <th colspan="2">{{__('payment.title.information')}}:</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>{{__('payment.table.client')}}</td>
                        <td><a class="hubers-link" href="{{route('clients.view',['id'=>$payment->client->id])}}"> {{$payment->client->name}} </a></td>
                    </tr>
                    <tr>
                        <td>{{__('payment.table.reservation')}}</td>

                        <td>
                            <a class="hubers-link" href="{{route('reservations.view',['id'=>$payment->reservation->id])}}"> {{$payment->reservation->description}} </a>
                         </td>
                    </tr>
                    <tr>
                        <td>{{__('payment.table.value')}}</td>
                        <td>{{ $payment->value }}</td>
                    </tr>
                    <tr>
                        <td>{{__('payment.table.description')}}</td>
                        <td>{{ $payment->notes }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
