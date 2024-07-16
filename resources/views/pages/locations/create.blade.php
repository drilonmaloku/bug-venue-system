@extends('layouts.app')

@section('header')
    Krijo Location
@endsection

@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    
                    <form role="form" method="POST"   action="{{ route('locations.store') }}"  enctype="multipart/form-data" onsubmit="return disableSubmitButton()">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Name</label>
                                    <input class="bug-text-input" type="text" name="name">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Slug</label>
                                    <input class="bug-text-input" type="text" name="slug">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <h3 class="ml-3">Create User</h3>
                        </div>
                        <div class="row mt-3">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Username*</label>
                                    <input class="bug-text-input" required type="text" name="username" >
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Emri*</label>
                                    <input class="bug-text-input" required type="text" name="first_name" >
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Mbiemri</label>
                                    <input class="bug-text-input" required type="text" name="last_name" >

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Emaili</label>
                                    <input class="bug-text-input" required type="text" name="email">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Telefoni</label>
                                    <input class="bug-text-input" required type="text" name="phone">
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
                                    <select required class="bug-text-input" name="role" id="">
                                        <option value="admin">Admin</option>
                                        <option value="super-admin" selected>Super Admin</option>
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

    <script>
        function disableSubmitButton() {
            document.getElementById("submitBtn").disabled = true;
            return true;
        }
    </script>
@endsection
