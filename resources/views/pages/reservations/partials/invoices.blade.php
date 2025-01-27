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
