@extends('layouts.app')
@section('header')
    {{__('reminders.edit_title')}}
@endsection
@section('content')
    <div class="vms_panel">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>{{__('reminders.reservation_title')}}: {{$reservation->title}} ({{$reservation->date}})</h4>
        </div>

        <form action="{{ route('reminders.update', ['reservation' => $reservation->id, 'reminder' => $reminder->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col-md-8">
                    <h6><strong>{{__('reminders.form.details')}}</strong></h6>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title" class="form-control-label">{{__('reminders.form.title')}} <span class="text-danger">*</span></label>
                                <input type="text" class="bug-text-input white medium @error('title') is-invalid @enderror" 
                                    id="title" name="title" value="{{ old('title', $reminder->title) }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="reminder_date" class="form-control-label">{{__('reminders.form.reminder_date')}} <span class="text-danger">*</span></label>
                                <input type="datetime-local" class="bug-text-input white medium @error('reminder_date') is-invalid @enderror" 
                                    id="reminder_date" name="reminder_date" value="{{ old('reminder_date', $reminder->reminder_date->format('Y-m-d\TH:i')) }}" required>
                                @error('reminder_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                     <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="type" class="form-control-label">{{__('reminders.form.type')}} <span class="text-danger">*</span></label>
                                <select class="hubers-select-input white medium @error('type') is-invalid @enderror" 
                                        id="type" name="type" required>
                                    <option value="">{{__('reminders.form.select_type')}}</option>
                                    <option value="in_app" {{ old('type', $reminder->type) == 'in_app' ? 'selected' : '' }}>{{__('reminders.form.type_in_app')}}</option>
                                    <option value="email" {{ old('type', $reminder->type) == 'email' ? 'selected' : '' }}>{{__('reminders.form.type_email')}}</option>
                                    <option value="both" {{ old('type', $reminder->type) == 'both' ? 'selected' : '' }}>{{__('reminders.form.type_both')}}</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="message" class="form-control-label">{{__('reminders.form.message')}} <span class="text-danger">*</span></label>
                                <textarea class="bug-text-input white medium @error('message') is-invalid @enderror" 
                                        id="message" name="message" rows="4" required>{{ old('message', $reminder->message) }}</textarea>
                                @error('message')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="hubers-btn">{{__('general.update_btn')}}</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection 