@extends('layouts.app')
@section('header')
    {{__('services.edit.title')}}: {{$invoice->id}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action="{{ route('reservations.invoice.update', ['id' => $reservation->id, 'invoiceId' => $invoice->id])}}"  enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('services.edit.form.value')}}*</label>
                                    <input class="bug-text-input" required type="number" name="amount" value="{{$invoice->amount}}">
                                    
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('services.edit.form.date')}}*</label>
                                <input class="bug-text-input" type="date" name="date" required id="dateInput" value="{{$invoice->date}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('services.edit.notes')}}</label>
                                    <input class="bug-text-input" required type="text" name="description" value="{{$invoice->description}}">
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
