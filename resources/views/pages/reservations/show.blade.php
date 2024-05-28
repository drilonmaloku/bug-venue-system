@extends('layouts.app')

@section('header')
   Rezervimi : {{$reservation->name}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
            <div class="d-flex">
                <form action="{{ route('reservation.destroy',$reservation->id) }}" method="POST" class="d-flex justify-content-end">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-danger btn-sm ms-auto mb-0" type="submit"><i class="fa fa-trash"></i> Fshij</button>
                </form>

               
                <a   href="{{route('reservation.edit',['id'=>$reservation->id])}}">
                    <button class="btn btn-success btn-sm ms-auto mb-0 ml-2" type="submit"><i class="fa fa-edit"></i> Perditeso</button>
                </a>
            </div>
                <div class="bug-table-item-options">
                <table>
                    <thead>
                    <tr>
                        <th colspan="2">Informatat:</th>
                    </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Data</td>
                            <td>{{ $reservation->date }}</td>
                        </tr>
                        <tr>
                            <td>Salla</td>
                            <td>{{ $reservation->venue->name }}</td>
                        </tr>
                        <tr>
                            <td>Numri i te ftuarve</td>
                            <td>{{ $reservation->number_of_guests}}</td>

                        </tr>
                        <tr>
                            <td>Klienti</td>
                            <td>{{ $reservation->client->name }}</td>
                        </tr>
                        <tr>
                            <td>Koha:</td>
                            <td>{{ $reservation->reservation_type_name}}</td>
                        </tr>
                        <tr>
                            <td>Pagesa Momentale:</td>
                            <td>{{ $reservation->current_payment}}€</td>
                        </tr>
                        <tr>
                            <td>Pagesa E Mbetur:</td>
                            <td>{{ $reservation->total_payment - $reservation->current_payment}}€</td>
                        </tr>
                        <tr>
                            <td>Pagesa Totale:</td>
                            <td>{{ $reservation->total_payment}}€</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    <div class="modal fade" id="reservationModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalLabel">Krijo Pagese</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

               
                <form role="form" method="POST" action="{{ route('reservations.payment.store', ['id' => $reservation->id]) }}" enctype="multipart/form-data">
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
                                    <input id="initial_payment_value" class="bug-text-input" type="number" name="initial_payment_value">
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
        <div class="d-flex justify-content-end mb-2">
            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#reservationModal">
                Shto pagese per kete rezervim
            </button>
        </div>
        <h5>Pagesat:</h5>
        @if(count($reservation->payments) > 0)
            <div class="table-responsive ">
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
                    @foreach($reservation->payments as $payment)
                        <tr>
                            <td>
                                {{$payment->id}}
                            </td>
                            <td>
                                {{$payment->value}}€
                            </td>
                            <td>
                                {{$payment->date}}
                            </td>
                            <td>
                                {{$payment->notes}}
                            </td>
                            <td>
                                {{$payment->description}}
                            </td>
                            
                            <td>
                                <div class="bug-table-item-options">
                                    <a class="bug-table-item-option" href="{{route('payments.view',['id'=>$payment->id])}}">
                                        <i class="fa fa-eye"></i>
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
            @if(count($reservation->comments) > 0)
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
                    @foreach($reservation->comments as $comment)
                    <tr>
                        <td>{{ $comment->user->first_name }}</td>
                        <td>
                            <p>{{ $comment->comment }}</p>
                        </td>
                        <td>{{ $comment->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($comment->user_id == auth()->id())
                            <div class="hubers-item-options">
                                <form action="{{ route('reservations.comment.delete', $comment->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm " type="submit"><i class="fa fa-trash"></i></button>
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


