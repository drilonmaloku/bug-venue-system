<div class="vms_panel">
    <div class="d-flex align-items-center justify-content-between">
        <h5>{{__('reservations.view.notes')}}:</h5>
        <a class="btn hubers-btn" href="{{route('reservations.editNotes',['id' =>$reservation->id])}}">{{__('reservations.view.notes_modify')}}</a>
    </div>
    @if ($reservation->notes)
        {!! json_decode($reservation->notes) !!}
    @else
        <div class="hubers-empty-tab">
            <h5 class="text-center">{{__('reservations.view.notes.empty')}}</h5>
        </div>
    @endif
</div>