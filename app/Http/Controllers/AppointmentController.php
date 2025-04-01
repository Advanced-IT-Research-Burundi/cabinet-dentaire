<?php

namespace App\Http\Controllers;

use App\Http\Requests\AppointmentStoreRequest;
use App\Http\Requests\AppointmentUpdateRequest;
use App\Models\Appointment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppointmentController extends Controller
{
    public function index(Request $request): Response
    {
        $appointments = Appointment::all();

        return view('appointment.index', [
            'appointments' => $appointments,
        ]);
    }

    public function create(Request $request): Response
    {
        return view('appointment.create');
    }

    public function store(AppointmentStoreRequest $request): Response
    {
        $appointment = Appointment::create($request->validated());

        $request->session()->flash('appointment.id', $appointment->id);

        return redirect()->route('appointments.index');
    }

    public function show(Request $request, Appointment $appointment): Response
    {
        return view('appointment.show', [
            'appointment' => $appointment,
        ]);
    }

    public function edit(Request $request, Appointment $appointment): Response
    {
        return view('appointment.edit', [
            'appointment' => $appointment,
        ]);
    }

    public function update(AppointmentUpdateRequest $request, Appointment $appointment): Response
    {
        $appointment->update($request->validated());

        $request->session()->flash('appointment.id', $appointment->id);

        return redirect()->route('appointments.index');
    }

    public function destroy(Request $request, Appointment $appointment): Response
    {
        $appointment->delete();

        return redirect()->route('appointments.index');
    }
}
