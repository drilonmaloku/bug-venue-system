@extends('layouts.app')

@section('header')
    Credit Deposit for Location: {{ $location->name }}
@endsection

@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action="{{ route('location-payments.credit-deposit.store', ['location_id' => $location->id]) }}" onsubmit="return disableSubmitButton()">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="amount" class="bug-label">Amount</label>
                                    <input class="bug-text-input" required type="number" step="0.01" name="amount" id="amount">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="credits" class="bug-label">Credits</label>
                                    <input class="bug-text-input" required type="number" name="credits" id="credits">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="notes" class="bug-label">Notes (Optional)</label>
                                    <textarea class="bug-text-input" name="notes" id="notes" rows="3"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button id="submitBtn" type="submit" class="hubers-btn">Deposit Credits</button>
                                <a href="{{ route('locations.edit', ['id' => $location->id]) }}" class="hubers-btn hubers-btn-secondary">Cancel</a>
                            </div>
                        </div>
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