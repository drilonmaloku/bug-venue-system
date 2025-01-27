@extends('layouts.app')

@section('header')
    {{__('reservations.view.notes.edit')}}
@endsection
@section('content')
    <div class="vms_panel">
        <form action="{{ route('reservations.updateNotes',['id'=>$reservation->id]) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="hubers-form-group">
                <textarea name="notes" id="contractContent" rows="20" >
                    {!! isset($reservation->notes) ? json_decode($reservation->notes) : '' !!}
                </textarea>
            </div>
            <button class="hubers-btn" type="submit">{{__('general.save_btn')}}</button>
        </form>
    </div>

    <script src="https://cdn.ckeditor.com/4.22.0/standard/ckeditor.js"></script>

    <script>
        CKEDITOR.replace('contractContent', {
            height: 600
        });
    </script>
@endsection
@section('scripts')

@endsection

<style>
    textarea {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
        resize: vertical;
        min-height: 100px;
    }

    .comment {
        background-color: #f9f9f9;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 10px;
        margin-bottom: 10px;
    }

    .comment p {
        margin: 0;
    }
</style>
