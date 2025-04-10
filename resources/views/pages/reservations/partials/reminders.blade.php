<div class="vms_panel">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5>{{__('reminders.title')}}:</h5>
        <a href="{{ route('reminders.create', ['reservationId' => $reservation->id]) }}" class="hubers-btn">
            <i class="fa fa-plus mr-2"></i> {{__('reminders.create_btn')}}
        </a>
    </div>

    @if($reservation->reminders && $reservation->reminders->count() > 0)
        <div class="table-responsive mt-3">
            <table class="bug-table">
                <thead>
                <tr>
                    <th>{{__('reminders.table.title')}}</th>
                    <th>{{__('reminders.table.message')}}</th>
                    <th>{{__('reminders.table.reminder_date')}}</th>
                    <th>{{__('reminders.table.type')}}</th>
                    <th>{{__('reminders.table.status')}}</th>
                    <th>{{__('reminders.table.created_by')}}</th>
                    <th width="80"></th>
                </tr>
                </thead>
                <tbody>
                @foreach($reservation->reminders->sortBy('reminder_date') as $reminder)
                    <tr>
                        <td>{{$reminder->title}}</td>
                        <td>{{Str::limit($reminder->message, 50)}}</td>
                        <td>{{$reminder->reminder_date->format('Y-m-d H:i')}}</td>
                        <td>
                            <span class="badge {{ $reminder->type === 'in_app' ? 'badge-info' : ($reminder->type === 'email' ? 'badge-primary' : 'badge-success') }}">
                                {{$reminder->type}}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $reminder->is_sent ? 'badge-success' : 'badge-warning' }}">
                                {{$reminder->is_sent ? __('reminders.status.sent') : __('reminders.status.pending')}}
                            </span>
                        </td>
                        <td>{{$reminder->creator->name}}</td>
                        <td>
                            <div class="bug-table-item-options">
                                <a href="{{ route('reminders.show', ['reservation' => $reservation->id, 'reminder' => $reminder->id]) }}" class="bug-table-item-option">
                                    <i class="fa fa-eye"></i>
                                </a>
                                <form class="mb-0 d-inline" action="{{ route('reminders.destroy', ['reservationId' => $reservation->id, 'id' => $reminder->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bug-table-item-option text-danger border-0">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="hubers-empty-tab">
            <h5 class="text-center">{{__('reminders.table.not_found')}}</h5>
        </div>
    @endif
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Confirmation for delete
        $('.delete-reminder-form').on('submit', function(e) {
            if (!confirm("{{__('reminders.confirm_delete')}}")) {
                e.preventDefault();
            }
        });
    });
</script>
@endpush 