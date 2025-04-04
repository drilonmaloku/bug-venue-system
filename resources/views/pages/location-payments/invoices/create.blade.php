@extends('layouts.app')

@section('header')
    {{__('dashboard.create_invoice')}} - {{ $location->name }}
@endsection

@section('content')
    <div class="vms_panel">
        <div class="row">
            <div class="col-md-8">
                <div class="bug-table-item-options">
                    <a class="bug-table-item-option ml-2" href="{{ route('location.invoices.index', $location) }}">
                        <i class="fa fa-arrow-left"></i>
                    </a>
                </div>

                <form action="{{ route('location.invoices.store', $location) }}" method="POST">
                    @csrf
                    
                    <div class="form-group">
                        <label for="credits">{{__('dashboard.credits')}}</label>
                        <input type="number" class="form-control @error('credits') is-invalid @enderror" 
                               id="credits" name="credits" value="{{ old('credits') }}" required min="1">
                        @error('credits')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="description">{{__('dashboard.description')}}</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="due_date">{{__('dashboard.due_date')}}</label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" 
                               id="due_date" name="due_date" value="{{ old('due_date') }}" required>
                        @error('due_date')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <button type="submit" class="hubers-btn">{{__('dashboard.create_invoice')}}</button>
                        <a href="{{ route('location.invoices.index', $location) }}" class="hubers-btn inverse">
                            {{__('general.cancel_btn')}}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection 