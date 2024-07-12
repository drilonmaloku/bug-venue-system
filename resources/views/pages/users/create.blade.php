@extends('layouts.app')
@section('header')
   Krijo Perdorues
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action={{ route('users.store') }} enctype="multipart/form-data" onsubmit="return disableSubmitButton();">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Username*</label>
                                    <div>
                                        @if(auth()->user()->getCurrentLocationId())
                                            <span>{{auth()->user()->getCurrentLocationSlug()}}_</span>
                                        @endif
                                    
                                        <input class="bug-text-input" type="text" name="username" >
                                    </div>
                               
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Emri*</label>
                                    <input class="bug-text-input" type="text" name="first_name" >
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Mbiemri</label>
                                    <input class="bug-text-input" type="text" name="last_name" >

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Emaili</label>
                                    <input class="bug-text-input" type="text" name="email">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Telefoni</label>
                                    <input class="bug-text-input" type="text" name="phone">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Password</label>
                                    <div class="password-input-toggle">
                                        <input id="password" type="password" class="bug-text-input " name="password" required >
                                        <span class="password-input-toggle-icon"><i class="fa fa-eye"></i></span>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Roli</label>
                                    <select class="bug-text-input" name="role" id="">
                                        <option value="admin">Admin</option>
                                        <option value="super-admin">Super Admin</option>
                                        <option value="manager">Manager</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <button id="submitBtn" type="submit" class="hubers-btn">Ruaj</button>


                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection



<script>
    function disableSubmitButton() {
        document.getElementById("submitBtn").disabled = true;
        return true;
    }
</script>