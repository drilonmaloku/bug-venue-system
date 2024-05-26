@extends('layouts.app')

@section('header')
    Krijo Menu
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action={{ route('menus.store') }} enctype="multipart/form-data">
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
                                    <label for="example-text-input" class="bug-label">Qmimi</label>
                                    <input class="bug-text-input" type="number" name="price">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="hubers-btn">Ruaj</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
