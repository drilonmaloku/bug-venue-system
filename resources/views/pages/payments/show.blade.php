@extends('layouts.app')

@section('header')
{{__('payment.title.single')}} : {{$payment->id}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
        <div class="col-md-8">
            <div class="bug-table-item-options">
                   <button class="btn btn-danger btn-sm ms-auto mr-2" data-toggle="modal" data-target="#deleteModal">
                       <i class="fa fa-trash"></i> {{__('payment.forms.delete')}}
                   </button>
                    <a class="bug-table-item-option" href="{{route('payments.edit',['id'=>$payment->id])}}">
                        <i class="fa fa-edit"></i>
                    </a>
                </div>
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
                        <td>{{__('payment.table.payment_method')}}</td>
                        <td>{{ $payment->paymentMethodLabel }}</td>
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


    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">{{__('payment.forms.confirm_delete')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{__('payment.forms.delete_confirmation_message')}}
            </div>
            <div class="modal-footer">
                <form action="{{ route('payments.destroy', $payment->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('general.cancel_btn')}}</button>
                    <button type="submit" class="btn btn-danger">{{__('payment.forms.confirm_delete_btn')}}</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
