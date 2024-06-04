@extends('layouts.app')
@section('header')
    Përditso Reservimin: {{$reservation->id}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    {{-- action="{{ route('reservations.update',['id'=>$reservation->id]) }}" --}}
                    <form role="form" method="POST" action="{{ route('reservations.update', ['id' => $reservation->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Numri i të ftuarve*</label>
                                    <input id="numberOfGuests" class="bug-text-input" type="number" name="number_of_guests" value="{{$reservation->number_of_guests}}" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Cmimi i menus*</label>
                                    <input id="menuPrice" class="bug-text-input" type="number" name="menu_price" value="{{$reservation->menu_price}}" >
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="example-text-input" class="form-control-label">Selekto Menagjerin</label>
                                    <select id="menuId" class="bug-text-input" name="menager_id">
                                        @foreach($users as $user)
                                        <option value="{{$user->id}}" {{$user->id == $reservation->menager_id ? 'selected' : ''}}>{{$user->first_name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="hubers-btn">Ruaj</button>


                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
