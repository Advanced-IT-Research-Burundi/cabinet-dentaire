@extends('layouts.app')
@section('title', 'Calendrier des rendez-vous')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4>Calendrier des rendez-vous</h4>
                </div>
                <div class="card-body">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
@endsection

@section('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales-all.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        // Récupération des événements depuis la route Laravel
        fetch('{{ route("appointments.events") }}')
            .then(response => response.json())
            .then(data => {
                console.log(data);
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'timeGridWeek',
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay'
                    },
                    locale: 'fr',
                    timeZone: 'local',
                    slotMinTime: '07:00:00',
                    slotMaxTime: '18:00:00',
                    allDaySlot: false,
                    nowIndicator: true,
                    height: 'auto',
                    events: data,
                    eventTimeFormat: {
                        hour: '2-digit',
                        minute: '2-digit',
                        hour12: false
                    },
                    eventClick: function(info) {
                        showEventDetails(info.event);
                    }
                });

                calendar.render();
            })
            .catch(error => console.error('Erreur lors de la récupération des événements:', error));
    });

    function showEventDetails(event) {
        // Affichage des détails du rendez-vous dans une modal
        var modalTitle = event.title;
        var modalContent = `
            <p><strong>Traitement:</strong> ${event.extendedProps.treatment}</p>
            <p><strong>Début:</strong> ${event.start.toLocaleString()}</p>
            <p><strong>Fin:</strong> ${event.end.toLocaleString()}</p>
        `;

        // Ici vous pouvez utiliser une modal Bootstrap ou SweetAlert2
        // Exemple avec SweetAlert2
        Swal.fire({
            title: modalTitle,
            html: modalContent,
            icon: 'info',
            confirmButtonText: 'Fermer'
});
    }
</script>
@endsection