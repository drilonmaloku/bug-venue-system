@extends('layouts.app')
@section('header')
    {{__('users.forms.create_title')}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <form role="form" method="POST" action={{ route('users.store') }} enctype="multipart/form-data" onsubmit="return disableSubmitButton();">
                    @csrf
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('users.table.username')}}*</label>
                                <div class="username-input">
                                    @if(auth()->user()->getCurrentLocationId())
                                        <span class="location_slug">{{auth()->user()->getCurrentLocationSlug()}}_</span>
                                    @endif
                                    <input class="bug-text-input" type="text" name="username" >
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('users.table.email')}}*</label>
                                <input class="bug-text-input" required placeholder="Emri" type="text" name="first_name" >
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('users.table.last_name')}}*</label>
                                <input class="bug-text-input" required placeholder="Mbiemri" type="text" name="last_name" >

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('users.table.email')}}*</label>
                                <input class="bug-text-input" required placeholder="Emaili*" type="text" name="email">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('users.table.phone_number')}}*</label>
                                <input class="bug-text-input" required placeholder="Telefoni" type="text" name="phone">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('users.table.language')}}</label>
                                <select class="bug-text-input" name="default_language">
                                    <option value="sq">{{__('users.table.language.sq')}}</option>
                                    <option value="en">{{__('users.table.language.en')}}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('users.table.password')}}</label>
                                <div class="password-input-toggle">
                                    <input id="password" type="password" class="bug-text-input " placeholder="Password" name="password" required >
                                    <span class="password-input-toggle-icon"><i class="fa fa-eye"></i></span>
                                </div>

                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('users.table.role')}}*</label>
                                <select class="bug-text-input" name="role" id="">
                                    <option value="admin">{{__('users.table.role.admin')}}</option>
                                    <option value="super-admin">{{__('users.table.role.super_admin')}}</option>
                                    <option value="manager">{{__('users.table.role.manager')}}</option>
                                    <option value="staff">{{__('users.table.role.staff')}}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <button id="submitBtn" type="submit" class="hubers-btn">{{__('general.save_btn')}}</button>
                </form>
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