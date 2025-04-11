@extends('layouts.app')

@section('header')
    Credit Deposit for Location: {{ $location->name }}
@endsection

@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action="{{ route('location-payments.credit-deposits.store', ['location' => $location->id]) }}" onsubmit="return disableSubmitButton()">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="payment_method" class="bug-label">Payment Method</label>
                                    <select class="bug-text-input" required name="payment_method" id="payment_method" onchange="toggleAmountField()">
                                        <option value="">Select Payment Method</option>
                                        <option value="cash">Cash</option>
                                        <option value="gift">Gift</option>
                                    </select>
                                </div>
                            </div>
                            <div id="amountField" class="col-md-12">
                                <div class="form-group">
                                    <label for="amount" class="bug-label">Amount (Optional - will be set equal to credits)</label>
                                    <input class="bug-text-input" type="number" step="0.01" name="amount" id="amount">
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

        function toggleAmountField() {
            const paymentMethod = document.getElementById('payment_method').value;
            const amountField = document.getElementById('amountField');
            
            if (paymentMethod === 'gift') {
                amountField.style.display = 'none';
                document.getElementById('amount').value = '';
            } else {
                amountField.style.display = 'block';
            }
        }

        // Initialize the form state
        document.addEventListener('DOMContentLoaded', function() {
            toggleAmountField();
        });
    </script>
@endsection