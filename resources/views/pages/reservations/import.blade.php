@extends('layouts.app')

@section('header')
    {{__('reservations.import.title')}}
@endsection

@section('content')
<div class="vms_panel">
    <div class="panel-content w-100">
        <div class="row">
            <div class="col-6">
                <div class="card">
                    <div class="card-body">
                        
                        <form action="{{ route('reservations.import') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group mb-4">
                                <label for="file">Select Excel File</label>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="file" name="file" accept=".xlsx,.xls" onchange="updateFileName()">
                                    <label class="custom-file-label" for="file" id="file-label">Choose file</label>
                                </div>

                               
                                @error('file')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            {{-- <div class="mb-4">
                                <h6>Required Columns:</h6>
                                <ul class="list-unstyled">
                                    <li>venue_id</li>
                                    <li>client_id</li>
                                    <li>menu_id</li>
                                    <li>manager_id</li>
                                    <li>menu_price</li>
                                    <li>date</li>
                                    <li>reservation_type (1, 2, or 3)</li>
                                    <li>description</li>
                                    <li>number_of_guests</li>
                                    <li>current_payment</li>
                                    <li>total_payment</li>
                                </ul>
                            </div> --}}

                            <div class="text-end">
                                <a href="{{ route('reservations.index') }}" class="hubers-btn inverse me-2">Cancel</a>
                                <button type="submit" class="hubers-btn">Import Reservations</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


 <script>
    function updateFileName() {
    var input = document.getElementById('file');
    var label = document.getElementById('file-label');
    var fileName = input.files[0].name;
    label.textContent = fileName;
        }
</script>