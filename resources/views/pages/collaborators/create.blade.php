@extends('layouts.app')

@section('header')
    Krijo Bashkpuntor
@endsection

@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action="{{ route('collaborators.store') }}" enctype="multipart/form-data" onsubmit="return disableSubmitButton()">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Emri*</label>
                                    <input class="bug-text-input" type="text" name="name">
                                </div>
                            </div>
                            <div class="col-md-12">
                                  <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Email</label>
                                    <input class="bug-text-input" type="text" name="email">
                                </div>
                            </div>
                            <div class="col-md-12">
                                    <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Numri i telefonit</label>
                                    <input class="bug-text-input" type="text" name="phone_number">
                                </div>
                            </div>
                            <div class="col-md-12">
                                    <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Lloji</label>
                                    <input class="bug-text-input" type="text" name="typeof">
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
