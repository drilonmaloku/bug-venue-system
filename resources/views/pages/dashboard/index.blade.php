@extends('layouts.app')
@section('header')
    Dashboard
@endsection
@section('content')
    <div class="vms_panel">
        <div id='calendar'></div>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                var calendarEl = document.getElementById('calendar');

                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    events: {!! json_encode($events) !!},
                    dateClick: function (info) {
                        // Open a modal or form to add a new event
                        alert('Clicked on: ' + info.dateStr);
                        alert('Coordinates: ' + info.jsEvent.pageX + ',' + info.jsEvent.pageY);
                        alert('Current view: ' + info.view.type);

                        // Example: Open a form in a modal
                        // You would need to implement this part based on your needs
                        // $('#myModal').modal('show');
                    }
                });

                calendar.render();
            });
        </script>
    </div>
@endsection
