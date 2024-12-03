@extends('layouts.app')

@section('header')
    <!-- Add any specific header content if needed -->
@endsection

@section('content')
    <div class="vms_panel">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-8">
                    <form role="form" method="POST" action="{{ route('reservations.updateEvent', ['id' => $reservation->id, 'eventId' => $event->id]) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                         
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="start_date" class="bug-label">Start Date*</label>
                                    <input class="bug-text-input" type="datetime-local" name="start_date" value="{{ $event->start_date->format('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="end_date" class="bug-label">End Date*</label>
                                    <input class="bug-text-input" type="datetime-local" name="end_date" value="{{ $event->end_date->format('Y-m-d\TH:i') }}" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="description" class="bug-label">Description</label>
                                    <textarea class="bug-text-input" name="description">{{ $event->description }}</textarea>
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="hubers-btn">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection