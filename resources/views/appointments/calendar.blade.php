@extends('layouts.app')

@section('title', 'Calendrier des rendez-vous')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="shadow-sm card">
                <div class="card-body">
                    <div style="display:none" class="mb-3 row">
                        <div class="col-md-6">
                            <div class="btn-group" role="group">
                                <button id="view-all" class="btn btn-outline-secondary active">Tous</button>
                                <button id="view-work-hours" class="btn btn-outline-secondary">Heures de travail</button>
                                <button id="view-overtime" class="btn btn-outline-secondary">Hors horaires</button>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-end">
                                <div class="legend d-flex align-items-center me-3">
                                    <span class="legend-color" style="background-color: #4CAF50;"></span>
                                    <span class="legend-text">Heures standard</span>
                                </div>
                                <div class="legend d-flex align-items-center">
                                    <span class="legend-color" style="background-color: #FF9800;"></span>
                                    <span class="legend-text">Hors horaires</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal pour afficher les détails d'un rendez-vous -->
<div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="border-0 shadow modal-content">
            <div class="py-3 text-white modal-header bg-primary position-relative">
                <div class="d-flex align-items-center">
                    <div class="appointment-status-indicator me-2" id="statusIndicator"></div>
                    <h5 class="m-0 modal-title fw-bold" id="appointmentTitle">Détails du rendez-vous</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="p-0 modal-body">
                <div class="p-3 appointment-header">
                    <div class="row align-items-center">
                        <div class="col-8">
                            <h4 id="patientName" class="mb-1 fw-bold">Nom du patient</h4>
                            <div class="d-flex align-items-center text-muted">
                                <i class="fas fa-user-md me-2"></i>
                                <span id="dentistName">Dr. Dentiste</span>
                            </div>
                        </div>

                    </div>
                </div>

                <div class="p-3 appointment-details border-top border-bottom">
                    <div class="mb-3 d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="calendar-icon me-3 text-primary">
                                <i class="fas fa-calendar-alt fa-lg"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Date</div>
                                <div id="appointmentDate" class="fw-bold">24 mai 2025</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="time-icon me-3 text-primary">
                                <i class="fas fa-clock fa-lg"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Heure</div>
                                <div id="appointmentTime" class="fw-bold">10:30 - 11:00</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center">
                            <div class="duration-icon me-3 text-primary">
                                <i class="fas fa-hourglass-half fa-lg"></i>
                            </div>
                            <div>
                                <div class="text-muted small">Durée</div>
                                <div id="appointmentDuration" class="fw-bold">30 min</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                      <p id="appointmentBadge" class="px-3 py-2 badge rounded-pill">Consultation</p>
                </div>
                <div class="p-3 appointment-notes">
                    <h6 class="mb-2 text-uppercase text-muted small fw-bold">Notes</h6>
                    <div id="appointmentNotes" class="p-3 rounded bg-light">
                        <p class="mb-0">Aucune note disponible</p>
                    </div>
                </div>
            </div>
            <div class="border-0 modal-footer d-flex justify-content-center">
                <button type="button" class="px-4 btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
<style>
    .legend {
        margin-left: 15px;
    }
    .legend-color {
        display: inline-block;
        width: 15px;
        height: 15px;
        border-radius: 3px;
        margin-right: 5px;
    }
    .fc-event {
        cursor: pointer;
        border-radius: 3px;
    }
    .fc-event.standard-hours {
        background-color: #4CAF50;
        border-color: #4CAF50;
    }
    .fc-event.overtime-hours {
        background-color: #FF9800;
        border-color: #FF9800;
    }
    .fc-event.urgent {
        background-color: #F44336;
        border-color: #F44336;
    }
    .fc-timegrid-slot {
        height: 30px !important;
    }
    .fc-view-harness {
        min-height: 700px;
    }
    .btn-group .btn.active {
        background-color: #007bff;
        color: white;
    }

    /* Styles pour le modal amélioré */
    .appointment-status-indicator {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        display: inline-block;
    }
    .appointment-status-indicator.urgent {
        background-color: #F44336;
    }
    .appointment-status-indicator.standard {
        background-color: #4CAF50;
    }
    .appointment-status-indicator.overtime {
        background-color: #FF9800;
    }
    .appointment-header {
        background-color: #f8f9fa;
    }
    #appointmentBadge {
        font-size: 0.9rem;
    }
    #appointmentBadge.Consultation {
        background-color: #2196F3;
    }
    #appointmentBadge.Détartrage {
        background-color: #00BCD4;
    }
    #appointmentBadge.Carie {
        background-color: #9C27B0;
    }
    #appointmentBadge.Canal {
        background-color: #673AB7;
    }
    #appointmentBadge.Couronne {
        background-color: #FF9800;
    }
    #appointmentBadge.Extraction {
        background-color: #FF5722;
    }
    #appointmentBadge.Implant {
        background-color: #795548;
    }
    #appointmentBadge.Autre {
        background-color: #607D8B;
    }

    /* Style pour les heures de travail personnalisées */
    .fc-timegrid-axis-cushion.fc-scrollgrid-shrink-cushion {
        font-weight: bold;
    }
    .fc-timegrid-axis-cushion.work-hours {
        color: #4CAF50;
    }
    .fc-timegrid-axis-cushion.after-hours {
        color: #FF9800;
    }
    .fc-timegrid-axis-cushion.next-day {
        color: #2196F3;
        font-style: italic;
    }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var appointmentModal = new bootstrap.Modal(document.getElementById('appointmentModal'));
    var currentView = 'all';
    var calendar;

    var calendarOptions = {
        initialView: 'timeGridWeek',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
        },
        locale: 'fr',
        timeZone: 'local',
        slotMinTime: '07:00:00',
        // slotMaxTime: '24:00:00',
        slotMaxTime: '24:00:00',
        slotDuration: '00:15:00',
        slotLabelInterval: '01:00:00',
        allDaySlot: false,
        nowIndicator: true,
        height: 'auto',
        expandRows: true,
        businessHours: {
            daysOfWeek: [0, 1, 2, 3, 4, 5, 6],
            startTime: '07:00',
            endTime: '24:00'
        },
        eventTimeFormat: {
            hour: '2-digit',
            minute: '2-digit',
            hour12: false
        },
        eventClassNames: function(arg) {
            var classes = [];
            var start = arg.event.start;
            var end = arg.event.end;

            // Vérifier si le rendez-vous est pendant les heures de travail
            var startHour = start.getHours();
            var endHour = end ? end.getHours() : (startHour + 1);
            var isWorkHours = (startHour >= 7 && startHour < 18) && (endHour >= 7 && endHour <= 18);

            // Utiliser les couleurs définies par le dentiste au lieu des classes
            // Les couleurs sont déjà définies dans backgroundColor et borderColor
            if (isWorkHours) {
                classes.push('standard-hours');
            } else {
                classes.push('overtime-hours');
            }

            return classes;
        },
        eventDidMount: function(info) {
            // Ajouter des infobulles aux événements
            var tooltip = new bootstrap.Tooltip(info.el, {
                title: `${info.event.title} - ${info.event.extendedProps.treatment}`,
                placement: 'top',
                trigger: 'hover',
                container: 'body'
            });
        },
        eventClick: function(info) {
            showEventDetails(info.event);
        }
    };

    // Récupération des événements depuis la route Laravel
    fetch('{{ route("appointments.events") }}')
        .then(response => response.json())
        .then(data => {
            console.log(data); // Afficher les données récupérées pour le débogage

            var events = data.map(function(event) {
                return {
                    id: event.id,
                    title: event.title,
                    start: event.start,
                    end: event.end,
                    backgroundColor: event.backgroundColor,
                    borderColor: event.borderColor,
                    extendedProps: {
                        patient: event.title,
                        treatment: event.treatment || '',
                        dentist: event.extendedProps.description || '',
                        notes: event.notes || '',
                        created_by: event.extendedProps.created_by || '',
                        urgent: false
                    }
                };
            });

            calendarOptions.events = events;
            calendar = new FullCalendar.Calendar(calendarEl, calendarOptions);
            calendar.render();

            setupFilterListeners();
        })
        .catch(error => {
            console.error('Erreur lors de la récupération des événements:', error);
            // Afficher un message d'erreur à l'utilisateur
            Swal.fire({
                title: 'Erreur',
                text: 'Impossible de charger les rendez-vous. Veuillez réessayer plus tard.',
                icon: 'error',
                confirmButtonText: 'OK'
            });

            // Initialiser quand même le calendrier sans événements
            calendar = new FullCalendar.Calendar(calendarEl, calendarOptions);
            calendar.render();

            setupFilterListeners();
        });

    // Configuration des filtres d'affichage
    function setupFilterListeners() {
        document.getElementById('view-all').addEventListener('click', function() {
            setActiveButton(this);
            currentView = 'all';
            refreshCalendarView();
        });

        document.getElementById('view-work-hours').addEventListener('click', function() {
            setActiveButton(this);
            currentView = 'work';
            refreshCalendarView();
        });

        document.getElementById('view-overtime').addEventListener('click', function() {
            setActiveButton(this);
            currentView = 'overtime';
            refreshCalendarView();
        });
    }

    function setActiveButton(button) {
        // Retirer la classe active de tous les boutons
        document.querySelectorAll('.btn-group .btn').forEach(function(btn) {
            btn.classList.remove('active');
        });
        // Ajouter la classe active au bouton cliqué
        button.classList.add('active');
    }

    function refreshCalendarView() {
        if (calendar) {
            calendar.getEvents().forEach(function(event) {
                var startHour = event.start.getHours();
                var endHour = event.end ? event.end.getHours() : (startHour + 1);
                var isWorkHours = (startHour >= 7 && startHour < 18) && (endHour >= 7 && endHour <= 18);

                // Filtrer les événements selon la vue actuelle
                if (currentView === 'all') {
                    event.setProp('display', 'auto');
                } else if (currentView === 'work' && isWorkHours) {
                    event.setProp('display', 'auto');
                } else if (currentView === 'overtime' && !isWorkHours) {
                    event.setProp('display', 'auto');
                } else {
                    event.setProp('display', 'none');
                }
            });
        }
    }

    function showEventDetails(event) {
        // Définir l'indicateur de statut (sans urgent car pas dans votre structure)
        var statusIndicator = document.getElementById('statusIndicator');
        var startHour = event.start.getHours();
        var endHour = event.end ? event.end.getHours() : (startHour + 1);
        var isWorkHours = (startHour >= 7 && startHour < 18) && (endHour >= 7 && endHour <= 18);

        // Nettoyer les classes précédentes
        statusIndicator.className = 'appointment-status-indicator me-2';

        if (isWorkHours) {
            statusIndicator.classList.add('standard');
        } else {
            statusIndicator.classList.add('overtime');
        }

        // Remplir les informations du modal
        document.getElementById('patientName').textContent = event.extendedProps.patient || 'Patient non spécifié';
        document.getElementById('dentistName').textContent = event.extendedProps.dentist || 'Dentiste non spécifié';

        // Badge de traitement avec couleur appropriée
        var badge = document.getElementById('appointmentBadge');
        var treatment = event.extendedProps.treatment || 'Non spécifié';
        badge.textContent = treatment;
        badge.className = 'badge rounded-pill px-3 py-2';

        // Utiliser la couleur du dentiste ou une couleur par défaut selon le traitement
        var treatmentColors = {
            'Consultation': '#2196F3',
            'Détartrage': '#00BCD4',
            'Carie': '#9C27B0',
            'Canal': '#673AB7',
            'Couronne': '#FF9800',
            'Extraction': '#FF5722',
            'Implant': '#795548'
        };

        var color = treatmentColors[treatment] || '#607D8B';
        badge.style.backgroundColor = color;

        // Date formatée
        var options = {
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric'
        };
        document.getElementById('appointmentDate').textContent =
            event.start.toLocaleDateString('fr-FR', options);

        // Heure formatée
        var startTime = event.start.toLocaleTimeString('fr-FR', {
            hour: '2-digit',
            minute: '2-digit'
        });
        var endTime = event.end ? event.end.toLocaleTimeString('fr-FR', {
            hour: '2-digit',
            minute: '2-digit'
        }) : 'Non définie';
        document.getElementById('appointmentTime').textContent = `${startTime} - ${endTime}`;

        // Durée calculée
        if (event.end) {
            var durationMs = event.end - event.start;
            var durationMinutes = Math.round(durationMs / (1000 * 60));
            document.getElementById('appointmentDuration').textContent = `${durationMinutes} min`;
        } else {
            document.getElementById('appointmentDuration').textContent = 'Non définie';
        }

        // Notes (pas disponibles dans votre structure actuelle)
        var notesElement = document.getElementById('appointmentNotes');
        if (event.extendedProps.notes && event.extendedProps.notes.trim() !== '') {
            notesElement.innerHTML = `<p class="mb-0">${event.extendedProps.notes}</p>`;
        } else {
            notesElement.innerHTML = '<p class="mb-0 text-muted fst-italic">Aucune note disponible</p>';
        }

        // Afficher le modal
        appointmentModal.show();
    }
});
</script>
@endpush
