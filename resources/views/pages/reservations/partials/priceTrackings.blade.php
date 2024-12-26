<div class="vms_panel">
    <h5>{{__('reservations.view.price_tracking.title')}}:</h5>
    @if (count($reservation->pricingTracking) > 0)
        <div class="table-responsive">
            <table class="bug-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>{{__('reservations.view.price_tracking.table.number_of_guests')}}</th>
                    <th>{{__('reservations.view.price_tracking.table.total_price')}}</th>
                    <th>{{__('reservations.view.price_tracking.table.menu_price')}}</th>
                    <th>{{__('reservations.view.price_tracking.table.discount')}}</th>
                    <th>{{__('reservations.view.price_tracking.table.services')}}</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($reservation->pricingTracking as $tracking)
                    <tr>
                        <td>
                            {{ $tracking->id }}
                        </td>
                        <td>
                            {{ $tracking->number_of_guests }}
                        </td>
                        <td>
                            {{ $tracking->total_price }}€
                        </td>
                        <td>
                            {{ $tracking->menu_price }}
                        </td>
                        <td>
                            {{ $tracking->total_discount_price }}
                        </td>
                        <td>
                            {{ $tracking->total_invoice_price }}
                        </td>


                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="hubers-empty-tab">
            <h5 class="text-center">Nuk ka ndryshime për këte rezervim.</h5>
        </div>
    @endif
</div>