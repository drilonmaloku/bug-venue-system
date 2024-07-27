@extends('layouts.app')

@section('header')
{{__('reservations.view.title')}} : {{ $reservation->name }}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <div class="d-flex">
                    <form action="{{ route('reservation.destroy', $reservation->id) }}" method="POST"
                        class="d-flex justify-content-end">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-danger btn-sm ms-auto mb-0" type="submit"><i class="fa fa-trash mr-2"></i>{{__('reservations.view.delete_btn_title')}}</button>
                    </form>


                    <a href="{{ route('reservation.edit', ['id' => $reservation->id]) }}">
                        <button class="btn btn-success btn-sm ms-auto mb-0 ml-2" type="submit">
                            <i class="fa fa-edit"></i>
                            {{__('reservations.view.update_btn_title')}}
                        </button>
                    </a>

{{--                    <a href="{{ route('reservations.printContract', ['id' => $reservation->id]) }}">--}}
{{--                        <button class="btn btn-info btn-sm ms-auto mb-0 ml-2" type="submit"><i class="fa fa-print"></i>--}}
{{--                            {{__('reservations.view.print_contract_btn_title')}}--}}
{{--                        </button>--}}
{{--                    </a>--}}
                </div>
                <table>
                    <thead>
                        <tr>
                            <th colspan="2">{{__('reservations.table.general_information')}}:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{__('reservations.table.date')}}</td>
                            <td>{{ $reservation->date }}</td>
                        </tr>
                        <tr>
                            <td>{{__('reservations.table.venue')}}</td>
                            <td> <a class="hubers-link"
                                    href="{{ route('venues.view', ['id' => $reservation->venue->id]) }}">
                                    {{ $reservation->venue->name }} </a>
                            </td>
                        </tr>
                        <tr>
                            <td>{{__('reservations.table.number_of_guests')}}</td>
                            <td>{{ $reservation->number_of_guests }}</td>
                        </tr>
                        <tr>
                            <td>{{__('reservations.table.client')}}</td>
                            <td>
                                <a class="hubers-link" href="{{ route('clients.view', ['id' => $reservation->client->id]) }}">{{ $reservation->client->name }} </a>
                            </td>
                        </tr>
                        <tr>
                            <td>{{__('reservations.table.created_date')}}</td>
                            <td>{{ $reservation->created_at }}</td>
                        </tr>
                        <tr>
                            <td>{{__('reservations.table.time')}}:</td>
                            <td>{{ $reservation->reservation_type_name }}</td>
                        </tr>
                    </tbody>
                </table>
                <table>
                    <thead>
                        <tr>
                            <th colspan="2">{{__('reservations.table.price_information')}}:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{__('reservations.table.menu_price')}}:</td>
                            <td>{{ $reservation->menu_price }}</td>
                        </tr>
                        <tr>
                            <td>{{__('reservations.table.menu_total_price')}}:</td>
                            <td>{{ $reservation->number_of_guests * $reservation->menu_price }}</td>
                        </tr>
                        <tr>
                            <td>{{__('reservations.table.total_services')}}:</td>
                            <td>{{ $totalInvoiceAmount }}€</td>
                        </tr>
                        <tr>
                            <td>{{__('reservations.table.total_discount')}}:</td>
                            <td>{{ $totalDiscount }}€</td>
                        </tr>
                        <tr>
                            <td>{{__('reservations.table.current_payment')}}:</td>
                            <td>{{ $reservation->current_payment }}€</td>
                        </tr>
                        <tr>
                            <td>{{__('reservations.table.payment_left')}}:</td>
                            <td>{{ $reservation->total_payment - $reservation->current_payment }}€</td>
                        </tr>
                        <tr>
                            <td>{{__('reservations.table.total_amount')}}:</td>
                            <td>{{ $totalAmount }}€</td>
                        </tr>
                        <tr>
                            <td>{{__('reservations.table.staff_expenses')}}:</td>
                            <td>{{ $reservation->staff_expenses }}€</td>
                        </tr>
                        <tr>
                            <td>{{__('reservations.table.manager')}}:</td>
                            <td>{{ $reservation->user ? $reservation->user->username : '' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="vms_panel">
        <div class="d-flex align-items-center justify-content-between">
            <h5>{{__('reservations.view.payments')}}:</h5>
            <a class="btn hubers-btn" data-toggle="modal" data-target="#reservationModal">{{__('reservations.view.add_payment')}}</a>
        </div>

        @if (count($reservation->payments) > 0)
            <div class="table-responsive mt-3 ">
                <table class="bug-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{__('reservations.view.payments.table.value')}}</th>
                            <th>{{__('reservations.view.payments.table.date')}}</th>
                            <th>{{__('reservations.view.payments.table.description')}}</th>
                            <th width="40">
                            </th>
                            <th></th>

                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservation->payments as $payment)
                            <tr>
                                <td>
                                    {{ $payment->id }}
                                </td>
                                <td>
                                    {{ $payment->value }}€
                                </td>
                                <td>
                                    {{ $payment->date }}
                                </td>
                                <td>
                                    {{ $payment->notes }}
                                </td>
                                <td>
                                    {{ $payment->description }}
                                </td>

                                <td>
                                    <div class="bug-table-item-options">
                                        <a class="bug-table-item-option" {{-- href="{{ route('payments.view', ['id' => $payment->id]) }}"> --}}
                                            href="{{ route('payments.view', ['id' => $payment->id]) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <a class="bug-table-item-option"
                                            href="{{ route('reservations.payment.edit', ['id' => $reservation->id, 'paymentId' => $payment->id]) }}">

                                            <i class="fa fa-edit"></i>
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
                <h5 class="text-center">Nuk ka Pagesa për këte rezervim.</h5>
            </div>
        @endif
    </div>
    <div class="vms_panel">
        <div class="d-flex align-items-center justify-content-between">
            <h5>{{__('reservations.view.services')}}:</h5>
            <a class="btn hubers-btn" data-toggle="modal" data-target="#reservationModalInvoice">{{__('reservations.view.add_service')}}</a>
        </div>
        @if (count($reservation->invoices) > 0)
            <div class="table-responsive mt-3">
                <table class="bug-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{__('reservations.view.services.table.value')}}</th>
                            <th>{{__('reservations.view.services.table.date')}}</th>
                            <th>{{__('reservations.view.services.table.description')}}</th>
                            <th width="40"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservation->invoices as $invoice)
                            <tr>
                                <td>
                                    {{ $invoice->id }}
                                </td>
                                <td>
                                    {{ $invoice->amount }}€
                                </td>
                                <td>
                                    {{ $invoice->date }}
                                </td>
                                <td>
                                    {{ $invoice->description }}
                                </td>
                                <td>
                                    <div class="bug-table-item-options">
                                        <a class="bug-table-item-option"
                                            href="{{ route('reservations.invoice.edit', ['id' => $reservation->id, 'invoiceId' => $invoice->id]) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form class="bug-table-item-option"
                                            action="{{ route('reservations.invoice.destroy', ['id' => $reservation->id, 'invoiceId' => $invoice->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="bug-table-item-option">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="hubers-empty-tab">
                <h5 class="text-center">{{__('reservations.view.no_services')}}</h5>
            </div>
        @endif
    </div>
    <div class="vms_panel">
        <div class="d-flex align-items-center justify-content-between">
            <h5>{{__('reservations.view.discounts')}}:</h5>
            <a class="btn hubers-btn" data-toggle="modal" data-target="#reservationModalDiscount">{{__('reservations.view.add_discount')}}</a>
        </div>
        @if (count($reservation->discounts) > 0)
            <div class="table-responsive mt-3">
                <table class="bug-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{__('reservations.view.discounts.table.value')}}</th>
                            <th>{{__('reservations.view.discounts.table.date')}}</th>
                            <th>{{__('reservations.view.discounts.table.description')}}</th>
                            <th width="60">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservation->discounts as $discount)
                            <tr>
                                <td>
                                    {{ $discount->id }}
                                </td>
                                <td>
                                    {{ $discount->amount }}€
                                </td>
                                <td>
                                    {{ $discount->date }}
                                </td>
                                <td>
                                    {{ $discount->description }}
                                </td>
                                <td>
                                    <div class="bug-table-item-options">
                                        <a class="bug-table-item-option"
                                            href="{{ route('reservations.discount.edit', ['id' => $reservation->id, 'discountId' => $discount->id]) }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form class="bug-table-item-option"
                                            action="{{ route('reservations.discount.destroy', ['id' => $reservation->id, 'discountId' => $discount->id]) }}"
                                            method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="bug-table-item-option" style="border:none;">
                                                <i class="fa fa-trash"></i>
                                            </button>

                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="hubers-empty-tab">
                <h5 class="text-center">{{__('reservations.view.no_discounts')}}</h5>
            </div>
        @endif
    </div>
    <div class="vms_panel">
        <h5>{{__('reservations.view.price_tracking.title')}}:</h5>
        @if (count($reservation->pricingTracking) > 0)
            <div class="table-responsive">
                <table class="bug-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>{{__('reservations.view.price_tracking.table.number_of_guests')}}</th>
                            <th>{{__('reservations.view.price_tracking.table.total_price')}}</th>
                            <th>{{__('reservations.view.price_tracking.table.menu_price')}}</th>
                            <th>{{__('reservations.view.price_tracking.table.discount')}}</th>
                            <th>{{__('reservations.view.price_tracking.table.services')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservation->pricingTracking as $tracking)
                            <tr>
                                <td>
                                    {{ $tracking->id }}
                                </td>
                                <td>
                                    {{ $tracking->number_of_guests }}
                                </td>
                                <td>
                                    {{ $tracking->total_price }}€
                                </td>
                                <td>
                                    {{ $tracking->menu_price }}
                                </td>
                                <td>
                                    {{ $tracking->total_discount_price }}
                                </td>
                                <td>
                                    {{ $tracking->total_invoice_price }}
                                </td>


                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="hubers-empty-tab">
                <h5 class="text-center">Nuk ka ndryshime për këte rezervim.</h5>
            </div>
        @endif
    </div>
    <div class="vms_panel">
        <div class="d-flex align-items-center justify-content-between">
            <h5>{{__('reservations.view.comments')}}:</h5>
            <a class="btn hubers-btn" data-toggle="modal" data-target="#reservationModalComment">{{__('reservations.view.add_comment')}}</a>
        </div>
        <div>
            @if (count($reservation->comments) > 0)
                <table class="hubers-table mt-4">
                    <thead>
                        <tr>
                            <th>{{__('"reservations.view.comments.table.user"')}}</th>
                            <th>{{__('"reservations.view.comments.table.comment"')}}</th>
                            <th>{{__('"reservations.view.comments.table.date"')}}</th>
                            <th width="60"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservation->comments as $comment)
                            <tr>
                                <td>{{ $comment->user->first_name }}</td>
                                <td>
                                    <p>{{ $comment->comment }}</p>
                                </td>
                                <td>{{ $comment->created_at->format('d/m/Y') }}</td>
                                <td>
                                    @if ($comment->user_id == auth()->id())
                                        <div class="hubers-item-options">
                                            <form action="{{ route('reservations.comment.delete', $comment->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm " type="submit"><i
                                                        class="fa fa-trash"></i></button>
                                            </form>
                                        </div>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="hubers-empty-tab">
                    <h5 class="text-center">{{__('reservations.view.no_comments')}}</h5>
                </div>
            @endif
        </div>
    </div>
    <div class="vms_panel">
        <div class="d-flex align-items-center justify-content-between">
            <h5>{{__('reservations.view.staff')}}:</h5>
            <a class="btn hubers-btn" data-toggle="modal" data-target="#reservationModalMembers">{{__('reservations.view.add_staff')}}</a>
        </div>
        <div>
            @if (count($reservation->reservationStaff) > 0)
                <table class="hubers-table mt-4">
                    <thead>
                        <tr>
                            <th>{{__('reservations.view.staff.table.name')}}</th>
                            <th width="60"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reservation->reservationStaff as $staff)
                            <tr>
                                <td>{{ $staff->user->first_name }} {{ $staff->user->last_name }}</td>

                                <td>
                                        <div class="hubers-item-options mt-3">
                                            <form action="{{ route('reservations.staff.delete', $staff->id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm " type="submit"><i
                                                        class="fa fa-trash"></i></button>
                                            </form>
                                        </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="hubers-empty-tab">
                    <h5 class="text-center">{{__('reservations.view.no_staff')}}</h5>
                </div>
            @endif
        </div>
    </div>
    <div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalLabel">{{__('reservations.view.add_payment')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form role="form" method="POST"
                      action="{{ route('reservations.payment.store', ['id' => $reservation->id]) }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="payment_date" class="form-control-label">{{__('reservations.view.form.date')}}</label>
                                    <input id="payment_date" required class="bug-text-input" type="date"
                                           name="payment_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="initial_payment_value" class="form-control-label">{{__('reservations.view.form.value')}}</label>
                                    <input id="initial_payment_value" required class="bug-text-input" type="number"
                                           name="initial_payment_value">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="payment_notes" class="form-control-label">{{__('reservations.view.form.notes')}}</label>
                                    <textarea id="payment_notes" required class="bug-text-input" name="payment_notes"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{__('general.save_btn')}}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('general.close_btn')}}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="modal fade" id="reservationModalInvoice" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalLabel">{{__('reservations.view.add_service')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <form role="form" method="POST" action="{{ route('reservations.invoice.store', ['id' => $reservation->id]) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice_date" class="form-control-label">{{__('reservations.view.services.form.date')}}</label>
                                    <input id="invoice_date" required class="bug-text-input" type="date" name="invoice_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice_amount" class="form-control-label">{{__('reservations.view.services.form.value')}}</label>
                                    <input id="invoice_amount" required class="bug-text-input" type="number"
                                           name="invoice_amount">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="invoice_description" class="form-control-label">{{__('reservations.view.services.form.notes')}}</label>
                                    <textarea id="invoice_description" required class="bug-text-input" name="invoice_description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{__('general.save_btn')}}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('general.close_btn')}}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="modal fade" id="reservationModalDiscount" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalLabel">{{__('reservations.view.add_discount')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form role="form" method="POST"
                      action="{{ route('reservations.discount.store', ['id' => $reservation->id]) }}"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_date" class="form-control-label">{{__('reservations.view.discounts.form.date')}}*</label>
                                    <input id="discount_date" required class="bug-text-input" type="date" name="discount_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="discount_amount" class="form-control-label">{{__('reservations.view.discounts.form.value')}}*</label>
                                    <input id="discount_amount" required class="bug-text-input" type="number"
                                           name="discount_amount">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="discount_description" class="form-control-label">{{__('reservations.view.discounts.form.notes')}}</label>
                                    <textarea id="discount_description" required class="bug-text-input" name="discount_description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{__('general.save_btn')}}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('general.close_btn')}}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="modal fade" id="reservationModalComment" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalCommentLabel">{{__('reservations.view.add_comment')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <form role="form"  action="{{ route('reservations.comment.store', ['id' => $reservation->id]) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="invoice_description" class="form-control-label">{{__("reservations.view.form.comment")}}</label>
                                    <textarea id="comment" required class="bug-text-input" name="comment"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{__('general.save_btn')}}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('general.close_btn')}}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="modal fade" id="reservationModalMembers" tabindex="-1" role="dialog"
         aria-labelledby="reservationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalMembersLabel">{{__('reservations.view.add_staff')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <form role="form"  action="{{ route('reservations.addMember', ['reservationId' => $reservation->id]) }}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">{{__('reservations.view.select_staff')}}*</label>

                                    <select required id="userid" class="bug-text-input" name="user_id">
                                        <option value="">{{__('reservations.view.select_staff')}}</option>
                                        @foreach($users as $user)
                                            <option value="{{$user->id}}">{{$user->first_name}} {{$user->last_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{__('general.save_btn')}}</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('general.close_btn')}}</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script>
        // Update the total price based on menu selection
        $('#menuId').on('change', function() {
            var price = $(this).find(':selected').data('price');
            $('#menuPrice').val(price);
            calculateTotal();
        });

        // Calculate total price
        function calculateTotal() {
            var menuPrice = parseFloat($('#menuPrice').val()) || 0;
            var totalPrice = menuPrice;
            $('#totalPrice').text(totalPrice);
        }
    </script>
@endsection

<style>
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        resize: vertical;
        min-height: 100px;
    }

    .comment {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
    }

    .comment p {
        margin: 0;
    }
</style>
