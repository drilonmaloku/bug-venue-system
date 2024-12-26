<div class="vms_panel">
    <div class="row">
        <div class="col-md-8">
            <div class="d-flex">
                <a class="hubers-btn" href="{{ route('reservation.edit', ['id' => $reservation->id]) }}">
                    <i class="fa fa-edit mr-2"></i>
                    {{__('reservations.view.update_btn_title')}}
                </a>
                <a class="hubers-btn ml-2" href="{{ route('reservations.printContract', ['id' => $reservation->id]) }}">
                    <i class="fa fa-print mr-2"></i>
                    {{__('reservations.view.print_contract_btn_title')}}
                </a>
                <a class="btn hubers-btn ml-2" data-toggle="modal" data-target="#contractPreview">
                    <i class="fa fa-file"></i>
                </a>
                <a class="btn hubers-btn danger ml-2" data-toggle="modal" data-target="#deleteReservation">
                    <i class="fa fa-trash"></i>
                </a>

            </div>
            <table>
                <thead>
                <tr>
                    <th colspan="2">{{__('reservations.table.general_information')}}:</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>{{__('reservations.table.status')}}</td>
                    <td>
                        <div class="status-column-wrapper">
                            <div class="status-box-wrapper">
                                <div class="status-box {{ $reservation->statusClass }}">

                                </div>
                                <strong>{{ $reservation->statusLabel }}</strong>
                            </div>
                            <a class="hubers-btn small"  data-toggle="modal" data-target="#updateStatusModal"><i class="fa fa-edit"></i></a>
                        </div>

                    </td>
                </tr>
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
                    <td>{{__('reservations.table.menu')}}:</td>
                    <td>{{ $reservation->menu_contents }}</td>
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
                <tr>
                    <td>{{__('reservations.table.decor')}}:</td>
                    <td>
                        @if ($reservation->decor)
                            <a href="{{ route('decors.view', $reservation->decor->id) }}">
                                {{ $reservation->decor->name }}
                            </a>
                        @else
                        @endif
                    </td>
                </tr>


                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="modal fade" id="deleteReservation" tabindex="-1" role="dialog"
     aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservationModalMembersLabel">{{__('reservations.view.delete_btn_title')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('reservation.destroy', $reservation->id) }}" method="POST" >
                @method('DELETE')
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                    <p>{{__("reservations.view.delete.note")}}</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">{{__('reservations.view.delete_btn_title')}}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('general.close_btn')}}</button>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="updateStatusModal" tabindex="-1" role="dialog"
     aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservationModalMembersLabel">{{__('reservations.view.update_status')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>


            <form role="form"  action="{{ route('reservations.updateStatus', ['id' => $reservation->id]) }}" method="POST"
                  enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="form-control-label">{{__('reservations.view.select_status')}}*</label>

                                <select required class="bug-text-input" name="status">
                                    <option value="">{{__('reservations.view.select_status')}}</option>
                                    <option @if($reservation->status == 1) selected @endif value="1">{{__('reservations.status.planned')}}</option>
                                    <option @if($reservation->status == 2) selected @endif value="2">{{__('reservations.status.finished')}}</option>
                                    <option @if($reservation->status == 3) selected @endif value="3">{{__('reservations.status.canceled')}}</option>

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
<div class="modal fade" id="contractPreview" tabindex="-1" role="dialog"
     aria-labelledby="reservationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reservationModalMembersLabel">{{__('reservations.view.contract_preview')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                {!! $contract !!}
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">{{__('general.save_btn')}}</button>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{__('general.close_btn')}}</button>
            </div>


        </div>
    </div>
</div>