





@extends('layouts.app')

@section('header')
Përditso Shpenzimin: {{$expense->id}}
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
                                <label for="example-text-input" class="bug-label">Data*</label>
                                <input class="bug-text-input" type="date" name="date" required id="dateInput" value="{{$expense->date}}">
                            </div>
                        </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Përshkrimi*</label>
                                    <input class="bug-text-input" placeholder="Pershkrimi*"  name="description" required value="{{$expense->description}}"></input>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Shuma*</label>
                                    <input class="bug-text-input" placeholder="Shuma*" type="number" name="amount" value="{{$expense->amount}}">
                                </div>
                            </div>
                        </div>
                        <button id="submitBtn" type="submit" class="hubers-btn">Ruaj</button>
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

