@extends('layouts.app')

@section('header')
    {{__('contracts.manage')}}
@endsection
@section('content')
    <div class="vms_panel">
        <form action="{{ route('location-settings.contract-save') }}" method="POST">
            @csrf
            <div class="hubers-form-group">
                <label class="bug-label" for="">{{__('contracts.contract')}}</label>

                <div>
                    {{__('contracts.info')}}
                    <div class="vms-tags">
                        <div class="vms-tag">@{{data}}</div>
                        <div class="vms-tag">@{{klienti}}</div>
                        <div class="vms-tag">@{{klienti_telefoni}}</div>
                        <div class="vms-tag">@{{salla}}</div>
                        <div class="vms-tag">@{{menu}}</div>
                        <div class="vms-tag">@{{id}}</div>
                        <div class="vms-tag">@{{qmimi_menus}}</div>
                        <div class="vms-tag">@{{numri_personav}}</div>
                        <div class="vms-tag">@{{pagesa_totale}}</div>
                    </div>
                </div>
                <textarea name="contractContent" id="contractContent" rows="20" >
                    {!! isset($location_contract) ? $location_contract : '' !!}
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
