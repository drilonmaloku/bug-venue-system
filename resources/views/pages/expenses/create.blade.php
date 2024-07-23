@extends('layouts.app')

@section('header')
    {{__('expenses.title.create')}}
@endsection

@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <form role="form" method="POST"   action="{{ route('expenses.store') }}"  enctype="multipart/form-data" onsubmit="return disableSubmitButton()">
                    @csrf
                    <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="example-text-input" class="bug-label">{{__('expenses.table.date')}}*</label>
                            <input class="bug-text-input" type="date" name="date" required id="dateInput">
                        </div>
                    </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('expenses.table.description')}}*</label>
                                <textarea class="bug-text-input" rows="4" required name="description" placeholder="Pershkrimi*"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('expenses.table.amount')}}*</label>
                                <input class="bug-text-input" type="number" required name="amount" placeholder="Shuma*">
                            </div>
                        </div>
                    </div>
                    <button id="submitBtn" type="submit" class="hubers-btn">{{__('expenses.table.save')}}</button>
                </form>
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
