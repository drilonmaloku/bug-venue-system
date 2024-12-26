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