@extends('layouts.app')

@section('header')
    Rezervimi : {{ $reservation->name }}
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
                        <button class="btn btn-danger btn-sm ms-auto mb-0" type="submit"><i class="fa fa-trash"></i>
                            Fshij</button>
                    </form>


                    <a href="{{ route('reservation.edit', ['id' => $reservation->id]) }}">
                        <button class="btn btn-success btn-sm ms-auto mb-0 ml-2" type="submit"><i class="fa fa-edit"></i>
                            Perditeso</button>
                    </a>

                    <a href="{{ route('reservations.printContract', ['id' => $reservation->id]) }}">

                        <button class="btn btn-info btn-sm ms-auto mb-0 ml-2" type="submit"><i class="fa fa-print"></i>
                            Printo Kontraten</button>
                    </a>
                </div>
                <table>
                    <thead>
                    <tr>
                        <th colspan="2">Informatat Gjenerale:</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Data</td>
                        <td>{{ $reservation->date }}</td>
                    </tr>
                    <tr>
                        <td>Salla</td>
                        <td> <a class="hubers-link"
                                href="{{ route('venues.view', ['id' => $reservation->venue->id]) }}">
                                {{ $reservation->venue->name }} </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Numri i te ftuarve</td>
                        <td>{{ $reservation->number_of_guests }}</td>
                    </tr>
                    <tr>
                        <td>Klienti</td>
                        <td>
                            <a class="hubers-link"
                               href="{{ route('clients.view', ['id' => $reservation->client->id]) }}">
                                {{ $reservation->client->name }} </a>
                        </td>
                    </tr>
                    <tr>
                        <td>Data e krijimit te Rezervimit</td>
                        <td>{{ $reservation->created_at }}</td>
                    </tr>
                    <tr>
                        <td>Koha:</td>
                        <td>{{ $reservation->reservation_type_name }}</td>
                    </tr>
                    </tbody>
                </table>
                <table>
                    <thead>
                    <tr>
                        <th colspan="2">Informatat E qmimit:</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>Qmimi i Menus:</td>
                        <td>{{ $reservation->menu_price }}</td>
                    </tr>
                    <tr>
                        <td>Qmimi total per Menu:</td>
                        <td>{{ $reservation->number_of_guests * $reservation->menu_price }}</td>
                    </tr>
                    <tr>
                        <td>Sherbimi Totali:</td>
                        <td>{{$totalInvoiceAmount}}€</td>
                    </tr>

                    <tr>
                        <td>Zbritja Totali:</td>
                        <td>{{$totalDiscount}}€</td>
                    </tr>
                    <tr>
                        <td>Pagesa E Kryer:</td>
                        <td>{{ $reservation->current_payment }}€</td>
                    </tr>
                    <tr>
                        <td>Pagesa E Mbetur:</td>
                        <td>{{ $reservation->total_payment - $reservation->current_payment }}€</td>
                    </tr>
                    <tr>
                        <td>Shuma Totale :</td>
                        <td>{{$totalAmount}}€</td>
                    </tr>
                    <tr>
                        <td>Shpenzimet e Stafit:</td>
                        <td>{{ $reservation->staff_expenses }}€</td>
                    </tr>
                            <tr>
                                <td>Menagjeri:</td>
                                {{-- <td>{{$reservation->user->username }}</td> --}}
                            </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalLabel">Krijo Pagese</h5>
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
                                    <label for="payment_date" class="form-control-label">Data e Pagesës</label>
                                    <input id="payment_date" class="bug-text-input" type="date" name="payment_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="initial_payment_value" class="form-control-label">Vlera e Pagesës</label>
                                    <input id="initial_payment_value" class="bug-text-input" type="number"
                                        name="initial_payment_value">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="payment_notes" class="form-control-label">Shenime</label>
                                    <textarea id="payment_notes" class="bug-text-input" name="payment_notes"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Ruaj</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Mbyll</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
    <div class="vms_panel">

        <div class="d-flex align-items-center justify-content-between">
            <h5>Pagesat:</h5>
            <a class="btn hubers-btn" data-toggle="modal" data-target="#reservationModal">Shto pages</a>
        </div>
     
        @if (count($reservation->payments) > 0)
            <div class="table-responsive mt-3 ">
                <table class="bug-table">
                    <thead>
                        <tr>
                            <th>ID</th>

                            <th>Vlera</th>
                            <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                Data</th>
                            <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                Pershkrimi</th>
                            <th width="40" class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
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
                                        <a class="bug-table-item-option"
                                            {{-- href="{{ route('payments.view', ['id' => $payment->id]) }}"> --}}
                                            href="{{ route('payments.view', ['id' => $payment->id]) }}">
                                            <i class="fa fa-eye"></i>
                                        </a>

                                        <a class="bug-table-item-option"
                                        href="{{ route('reservations.payment.edit', ['id' => $reservation->id, 'paymentId' => $payment->id])  }}">

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

    {{-- Sherbimet --}}
    <div class="modal fade" id="reservationModalInvoice" tabindex="-1" role="dialog"
        aria-labelledby="reservationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalLabel">Shto Shërbim</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <form role="form" method="POST"
                    action="{{ route('reservations.invoice.store', ['id' => $reservation->id]) }}"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice_date" class="form-control-label">Data e Sherbimit</label>
                                    <input id="invoice_date" class="bug-text-input" type="date" name="invoice_date">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="invoice_amount" class="form-control-label">Vlera e Shërbimit</label>
                                    <input id="invoice_amount" class="bug-text-input" type="number" name="invoice_amount">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="invoice_description" class="form-control-label">Shënime</label>
                                    <textarea id="invoice_description" class="bug-text-input" name="invoice_description"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Ruaj</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Mbyll</button>
                    </div>
                </form>

            </div>
        </div>
    </div>

    <div class="vms_panel">
        <div class="d-flex align-items-center justify-content-between">
            <h5>Shërbimet:</h5>
            <a class="btn hubers-btn" data-toggle="modal" data-target="#reservationModalInvoice">Shto shërbim</a>
        </div>
   
   
        @if (count($reservation->invoices) > 0)
            <div class="table-responsive mt-3">
                <table class="bug-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Vlera</th>
                            <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                Data</th>
                            <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                Përshkrimi</th>
                            <th width="40" class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                            </th>
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
                                            href="{{ route('reservations.invoice.edit', ['id' => $reservation->id, 'invoiceId' => $invoice->id])  }}">
                                            <i class="fa fa-edit"></i>
                                        </a>
                                        <form class="bug-table-item-option" action="{{ route('reservations.invoice.destroy',  ['id' => $reservation->id, 'invoiceId' => $invoice->id]) }}"
                                              method="POST">
                                            @csrf
                                            @method('DELETE')
                                                <button type="submit" class="bug-table-item-option"
                                                        >
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
                <h5 class="text-center">Nuk ka sherbime ekstra për këte rezervim.</h5>
            </div>
        @endif
    </div>
       <div class="modal fade" id="reservationModalDiscount" tabindex="-1" role="dialog"
       aria-labelledby="reservationModalLabel" aria-hidden="true">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="reservationModalLabel">Krijo Zbritje</h5>
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
                                   <label for="discount_date" class="form-control-label">Data e Zbritjes</label>
                                   <input id="discount_date" class="bug-text-input" type="date" name="discount_date">
                               </div>
                           </div>
                           <div class="col-md-6">
                               <div class="form-group">
                                   <label for="discount_amount" class="form-control-label">Vlera e Zbritjes</label>
                                   <input id="discount_amount" class="bug-text-input" type="number"
                                       name="discount_amount">
                               </div>
                           </div>
                           <div class="col-md-12">
                               <div class="form-group">
                                   <label for="discount_description" class="form-control-label">Shenime</label>
                                   <textarea id="discount_description" class="bug-text-input" name="discount_description"></textarea>
                               </div>
                           </div>
                       </div>
                   </div>
                   <div class="modal-footer">
                       <button type="submit" class="btn btn-primary">Ruaj</button>
                       <button type="button" class="btn btn-secondary" data-dismiss="modal">Mbyll</button>
                   </div>
               </form>

           </div>
       </div>
   </div>

    <div class="vms_panel">
 
       <div class="d-flex align-items-center justify-content-between">
        <h5>Zbritjet:</h5>
        <a class="btn hubers-btn" data-toggle="modal" data-target="#reservationModalDiscount">Shto zbritje</a>
    </div>
       @if (count($reservation->discounts) > 0)
           <div class="table-responsive mt-3">
               <table class="bug-table">
                   <thead>
                       <tr>
                           <th>ID</th>

                           <th>Vlera</th>
                           <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                               Data</th>
                           <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                               Pershkrimi</th>
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
                                           href="{{ route('reservations.discount.edit', ['id' => $reservation->id, 'discountId' => $discount->id])  }}">
                                           <i class="fa fa-edit"></i>
                                       </a>
                                       <form class="bug-table-item-option" action="{{route('reservations.discount.destroy', ['id' => $reservation->id, 'discountId' => $discount->id])}}" method="POST" style="display:inline;">
                                           @csrf
                                           @method('DELETE')

                                           <button type="submit" class="bug-table-item-option"
                                                   style="border:none;">
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
               <h5 class="text-center">Nuk ka zbritje ekstra për këte rezervim.</h5>
           </div>
       @endif
   </div>
    <div class="vms_panel">

        <h5>Pricing Tracking for Reservation:</h5>
        @if (count($reservation->pricingTracking) > 0)
            <div class="table-responsive ">
                <table class="bug-table">
                    <thead>
                        <tr>
                            <th>ID</th>

                            <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                Numri i te ftuarve</th>
                            <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                Cmimi total</th>
                            <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                Cmimi i menus
                            </th>
                            <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                Zbritja
                            </th>
                            <th class="text-uppercase text-secondary text-sm font-weight-bolder opacity-7">
                                Sherbimi
                            </th>

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
        <div id="commentForm" class="hubers-panel-bordered" style="display: block;">
            <form action="{{ route('reservations.comment.store', ['id' => $reservation->id]) }}" method="POST">
                @csrf
                <div class="commentArea">

                    <h5 for="comment">Shto koment per rezervim:</h5>
                    <textarea name="comment" id="comment" required placeholder="Shto komentin per rezervim ketu"></textarea>
                </div>
                <div class="d-flex justify-content-end mt-2">
                    <button type="submit" class="hubers-btn" id="submitBtn">Shto</button>
                </div>
            </form>
        </div>
        <div class="hubers-panel-bordered" style="width: 100%;">
            @if (count($reservation->comments) > 0)
                <table class="hubers-table mt-4">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Comment</th>
                            <th>Date</th>
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
                    <h5 class="text-center">Nuk ka komente për këte rezervim.</h5>
                </div>
            @endif
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
