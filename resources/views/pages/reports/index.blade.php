@extends('layouts.app')
@section('header')
    Gjenero Raport
@endsection
@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="GET" action={{ route('reports.generate') }} enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="example-text-input" class="bug-label">Periudha:</label>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <input class="bug-text-input" type="date" name="starting_date" >
                                        </div>
                                        <div class="col-md-6">
                                            <input class="bug-text-input" type="date" name="ending_date" >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="hubers-btn">Gjenero</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
