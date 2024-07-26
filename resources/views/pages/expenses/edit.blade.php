@extends('layouts.app')

@section('header')
{{__('expenses.title.edit')}}: {{$expense->id}}
@endsection

@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    
                    <form role="form" method="POST" action="{{ route('expenses.update',['id'=>$expense->id]) }}"   enctype="multipart/form-data" onsubmit="return disableSubmitButton()">
                        @csrf
                        @method('PUT')
                        <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('expenses.table.date')}}*</label>
                                <input class="bug-text-input" type="date" name="date" required id="dateInput" value="{{$expense->date}}">
                            </div>
                        </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('expenses.table.description')}}*</label>
                                    <input class="bug-text-input" placeholder="{{__('expenses.table.description')}}*"   name="description" required value="{{$expense->description}}"></input>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">{{__('expenses.table.amount')}}*</label>
                                    <input class="bug-text-input" placeholder="{{__('expenses.table.amount')}}*" required type="number" name="amount" value="{{$expense->amount}}">
                                </div>
                            </div>
                        </div>
                        <button id="submitBtn" type="submit" class="hubers-btn">{{__('general.save_btn')}}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function disableSubmitButton() {
            document.getElementById("submitBtn").disabled = true;
            return true;
        }
    </script>
@endsection

