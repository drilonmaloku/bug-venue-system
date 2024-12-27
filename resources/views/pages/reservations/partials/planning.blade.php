<div class="vms_panel">
    <div class="d-flex align-items-center justify-content-between">
        <h5>{{__('reservation.planning.title')}}</h5>
        <a class="btn hubers-btn" data-toggle="modal" data-target="#planningModal">{{__('reservation.planning.modify')}}</a>
    </div>
    <div>
        @if (!empty($planning))
            <table class="hubers-table mt-4">
                <thead>
                    <tr>
                        <th>{{__('reservation.planning.start_time')}}</th>
                        <th>{{__('reservation.planning.end_time')}}</th>
                        <th>{{__('reservation.planning.description')}}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($planning as $plan)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($plan['start_time'])->format('d/m/Y H:i') }}</td>
                        <td>{{ \Carbon\Carbon::parse($plan['end_time'])->format('d/m/Y H:i') }}</td>
                        <td>{{ $plan['description'] }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <div class="hubers-empty-tab">
                <h5 class="text-center">{{__('reservation.planning.empty')}}</h5>
            </div>
        @endif
    </div>
</div>
<div class="modal  fade" id="planningModal" tabindex="-1" role="dialog" aria-labelledby="planningModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content ">
            <div class="modal-header">
                <h5 class="modal-title" id="planningModalLabel">{{__('reservation.planning.modify_planning')}}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form role="form" method="POST" action="{{ route('reservations.updatePlanning', ['id' => $reservation->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body" id="planning-container">
                    @if (!empty($planning) && is_array($planning))
                        @foreach ($planning as $index => $plan)
                            <div class="planning-item" plan-id="{{ $index }}">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">{{ __('reservation.planning.start_time') }}</label>
                                            <input class="bug-text-input" type="datetime-local" name="planning[{{ $index }}][start_time]" value="{{ $plan['start_time'] }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="">{{ __('reservation.planning.end_time') }}</label>
                                            <input class="bug-text-input" type="datetime-local" name="planning[{{ $index }}][end_time]" value="{{ $plan['end_time'] }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <label for="">{{ __('reservation.planning.description') }}</label>
                                        <textarea class="bug-text-input" placeholder="Përshkrimi" rows="2" name="planning[{{ $index }}][description]">{{ $plan['description'] }}</textarea>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <button type="button" class="btn hubers-btn danger btn-delete-plan" data-plan-id="{{ $index }}">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                </div>
                                <hr>
                            </div>
                            <hr>
                        @endforeach
                    @else
                        <p>{{ __('reservation.planning.no_planning_found') }}</p>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="add-plan-btn">
                        <i class="fa fa-plus"></i>
                    </button>
                    <button type="submit" class="btn btn-primary">{{ __('general.save_btn') }}</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('general.close_btn') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const addPlanBtn = document.getElementById('add-plan-btn');
        const planningContainer = document.getElementById('planning-container');
        let planIndex = {{ isset($planning) ? count($planning) : 0 }}; // Start index for new plans

        addPlanBtn.addEventListener('click', function () {
            // Create a new planning item
            const newPlanHtml = `
                <div class="planning-item" plan-id="${planIndex}">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ __('reservation.planning.start_time') }}</label>
                                <input class="bug-text-input" type="datetime-local" name="planning[${planIndex}][start_time]" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">{{ __('reservation.planning.end_time') }}</label>
                                <input class="bug-text-input" type="datetime-local" name="planning[${planIndex}][end_time]" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label for="">{{ __('reservation.planning.description') }}</label>
                            <textarea class="bug-text-input" placeholder="Përshkrimi" rows="2" name="planning[${planIndex}][description]"></textarea>
                        </div>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn hubers-btn danger btn-delete-plan" data-plan-id="${planIndex}">
                            <i class="fa fa-trash"></i>
                        </button>
                    </div>
                    <hr>
                </div>     <hr>
            `;
            // Append the new plan to the container
            planningContainer.insertAdjacentHTML('beforeend', newPlanHtml);
            planIndex++;
        });

        // Delete a plan dynamically
        planningContainer.addEventListener('click', function (event) {
            if (event.target.closest('.btn-delete-plan')) {
                const planItem = event.target.closest('.planning-item');
                planItem.remove();
            }
        });
    });
</script>