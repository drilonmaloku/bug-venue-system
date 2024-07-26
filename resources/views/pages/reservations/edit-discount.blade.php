@extends('layouts.app')
@section('header')
    {{__('discount.edit.title')}}: {{$discount->id}}
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
                                    <label for="example-text-input" class="bug-label">{{__('discount.edit.form.value')}}*</label>
                                    <input class="bug-text-input" required type="number" name="discount" value="{{$discount->amount}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('discount.edit.form.date')}}*</label>
                                <input class="bug-text-input" type="date" name="date" required id="dateInput" value="{{$discount->date}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('discount.edit.notes')}}</label>
                                    <input class="bug-text-input" type="text" name="description" value="{{$discount->description}}">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="hubers-btn">{{__('general.save_btn')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
