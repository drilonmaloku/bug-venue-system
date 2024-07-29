@extends('layouts.app')
@section('header')
    {{__('reports.title')}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <form role="form" method="GET" action={{ route('reports.generate') }} enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="example-text-input" class="bug-label">{{__('reports.period')}}:</label>
                                <div class="row">
                                    <div class="col-md-6">
                                        <input class="bug-text-input mb-2" type="date" name="starting_date" >
                                    </div>
                                    <div class="col-md-6">
                                        <input class="bug-text-input mb-2" type="date" name="ending_date" >
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="submit" class="hubers-btn">{{__('reports.generate')}}</button>
                </form>
            </div>
        </div>
    </div>
@endsection
