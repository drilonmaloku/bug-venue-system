@extends('layouts.app')

@section('header')
    Krijo Sallë
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action={{ route('venues.store') }} enctype="multipart/form-data" onsubmit="return disableSubmitButton();">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Emri*</label>
                                    <input class="bug-text-input" type="text" name="name" >
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Përshkrimi</label>
                                    <textarea class="bug-text-input" rows="4" name="description" ></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Kapaciteti</label>
                                    <input class="bug-text-input" type="number" name="capacity">
                                </div>
                            </div>
                        </div>
                        <button id="submitBtn" type="submit" class="hubers-btn">Ruaj</button>
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
