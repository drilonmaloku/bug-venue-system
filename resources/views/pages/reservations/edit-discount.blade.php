@extends('layouts.app')
@section('header')
    Përditso Zbritjen: {{$discount->id}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                   <form role="form" method="POST" action="{{ route('reservations.discount.update', ['id' => $reservation->id, 'discountId' => $discount->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Zbritja*</label>
                                    <input class="bug-text-input" type="number" name="discount" value="{{$discount->amount}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Data*</label>
                                <input class="bug-text-input" type="date" name="date" required id="dateInput" value="{{$discount->date}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Pershkrimi</label>
                                    <input class="bug-text-input" type="text" name="description" value="{{$discount->description}}">
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
