@extends('layouts.app')
@section('header')
    {{__('guests.title')}}
@endsection
@section('header-actions')
    <a class="btn hubers-btn" data-toggle="modal" data-target="#addGuestModal">{{__('general.create_btn')}}</a>
@endsection
@section('content')
    <div class="vms_panel">
        @if(count($guests) > 0)
            <div class="table-responsive p-0">
                <table class="bug-table">
                    <thead>
                    <tr>
                        <th width="40">
                            <input class="main-checkbox bug-checkbox-input" type="checkbox">
                        </th>
                        <th>{{__('guests.table.name')}}</th>
                        <th>{{__('guests.table.phone_number')}}</th>
                        <th>{{__('guests.table.email')}}</th>
                        <th>{{__('guests.table.guest_count')}}</th>
                        <th>{{__('guests.table.status')}}</th>
                        <th>{{__('guests.table.check_in_status')}}</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($guests as $guest)
                        <tr>
                            <td>
                                <input class="table-checkbox bug-checkbox-input" type="checkbox" value="{{$guest->id}}">
                            </td>
                            <td>
                                {{$guest->name}}
                            </td>
                            <td>
                                {{$guest->phone_number}}
                            </td>
                            <td>
                                {{$guest->email}}
                            </td>
                            <td>
                                {{$guest->guest_count}}
                            </td>
                            <td>
                                @if($guest->status == 1)
                                    <span class="badge bg-info text-white px-2 py-1">
                                        {{ __('guests.status.not_confirmed') }}
                                    </span>
                                    <form
                                            action="{{ route('reservations.updateGuestStatus', ['reservationId' =>$reservation->id ,'guestId' => $guest->id]) }}"
                                            method="POST"
                                            style="display: inline;"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="2">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </form>
                                    <form
                                            action="{{ route('reservations.updateGuestStatus', ['reservationId' =>$reservation->id ,'guestId' => $guest->id]) }}"
                                            method="POST"
                                            style="display: inline;"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="3">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </form>
                                @elseif($guest->status == 2)
                                    <span class="badge bg-success text-white px-2 py-1">
                                        {{ __('guests.status.confirmed') }}
                                    </span>
                                    <form
                                            action="{{ route('reservations.updateGuestStatus', ['reservationId' =>$reservation->id ,'guestId' => $guest->id]) }}"
                                            method="POST"
                                            style="display: inline;"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="3">
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </form>
                                @else
                                    <span class="badge bg-danger text-white px-2 py-1">
                                        {{ __('guests.status.rejected') }}
                                    </span>
                                    <form
                                            action="{{ route('reservations.updateGuestStatus', ['reservationId' =>$reservation->id ,'guestId' => $guest->id]) }}"
                                            method="POST"
                                            style="display: inline;"
                                    >
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="2">
                                        <button type="submit" class="btn btn-sm btn-success">
                                            <i class="fa fa-check"></i>
                                        </button>
                                    </form>
                                @endif

                            </td>
                            <td>
                                @if($guest->is_checked_in)
                                    <div class="d-flex align-items-center">
                                             <span class="badge bg-success text-white px-2 py-1 d-flex mr-2" style="width: 40px;height: 20px;border-radius: 10px"></span>
                                        <form
                                                action="{{ route('reservations.updateGuestCheckin', ['reservationId' =>$reservation->id ,'guestId' => $guest->id]) }}"
                                                method="POST"
                                                style="display: inline;"
                                        >
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="check_in_status" value="0">
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fa fa-times"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div class="d-flex align-items-center">
                                            <span class="badge bg-danger text-white px-2 py-1 mr-2 d-flex" style="width: 40px;height: 20px;border-radius: 10px"></span>
                                        <form
                                                action="{{ route('reservations.updateGuestCheckin', ['reservationId' =>$reservation->id ,'guestId' => $guest->id]) }}"
                                                method="POST"
                                                style="display: inline;"
                                        >
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="check_in_status" value="1">
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="fa fa-check"></i>
                                            </button>
                                        </form>
                                    </div>

                                @endif
                            </td>
                            <td>
                                <div class="bug-table-item-options">
                                    <form
                                            class="bug-table-item-option"
                                            action="{{ route('reservations.deleteGuest', ['reservationId' => $reservation->id, 'guestId' => $guest->id]) }}"
                                            method="POST"
                                            onsubmit="return confirm('Are you sure you want to delete this guest?');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" style="border: none; background: none; cursor: pointer; color: inherit; padding: 0;">
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
                @if ($is_on_search)
                    <h5 class="text-center">{{__('clients.table.not_found_with_search')}}</h5>
                    @else
                    <h5 class="text-center">{{__('clients.table.not_found_without_search')}}</h5>
                @endif
            </div>
        @endif
    </div>
    <div class="modal fade" id="addGuestModal" tabindex="-1" role="dialog" aria-labelledby="reservationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservationModalLabel">{{__('guests.add')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form role="form" method="POST" action="{{ route('reservations.addGuest', ['id' => $reservation->id]) }}">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" name="reservation_id" value="{{ $reservation->id }}">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="addguest_name" class="form-control-label">{{__('guests.form.name')}}*</label>
                                    <input id="addguest_name" required class="bug-text-input" type="text" name="name">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="addguest_email" class="form-control-label">{{__('guests.form.email')}}</label>
                                    <input id="addguest_email" class="bug-text-input" type="email" name="email">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="addguest_phone" class="form-control-label">{{__('guests.form.phone_number')}}</label>
                                    <input id="addguest_phone" class="bug-text-input" name="phone_number" />
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="addguest_count" class="form-control-label">{{__('guests.form.guest_count')}}</label>
                                    <input id="addguest_count" class="bug-text-input" type="number" name="guest_count" value="1">
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
