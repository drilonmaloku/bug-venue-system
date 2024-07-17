@extends('layouts.app')

@section('header')
    Krijo Tiket
@endsection

@section('content')
    <div class="vms_panel">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST"   action="{{ route('support-tickets.store') }}"  enctype="multipart/form-data" onsubmit="return disableSubmitButton()">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Titulli</label>
                                    <input class="bug-text-input"  name="title"></input>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Përshkrimi</label>
                                    <textarea class="bug-text-input" rows="4" name="description"></textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Attachment:</label>
                                    <label for="form_input_attachment" class="file-uploader mb-2">
                                        <i data-name="upload" data-tags="" data-type="upload" class="vue-feather vue-feather--upload">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-upload vue-feather__content"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path><polyline points="17 8 12 3 7 8"></polyline><line x1="12" y1="3" x2="12" y2="15"></line></svg>
                                        </i>
                                        <input id="form_input_attachment" name="attachment" type="file">
                                        <p>Upload File</p>
                                    </label>
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
