@extends('layouts.app')
@section('header')
{{__('payment.title.update')}}: {{$payment->id}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                   
                    <form role="form" method="POST" action="{{ route('payments.update',['id'=>$payment->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('payment.table.value')}}*</label>
                                    <input class="bug-text-input" type="text" name="value" value="{{$payment->value}}">
                                    
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('payment.table.date')}}*</label>
                                <input class="bug-text-input" type="date" name="date" required id="dateInput" value="{{$payment->date}}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('payment.table.description')}}</label>
                                    <input class="bug-text-input" type="text" name="notes" value="{{$payment->notes}}">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="hubers-btn">{{__('payment.forms.save')}}</button>


                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
