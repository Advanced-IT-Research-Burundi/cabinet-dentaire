<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Appointment;
use App\Models\Creator;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\PlannedTreatment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\AppointmentController
 */
final class AppointmentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $appointments = Appointment::factory()->count(3)->create();

        $response = $this->get(route('appointments.index'));

        $response->assertOk();
        $response->assertViewIs('appointment.index');
        $response->assertViewHas('appointments');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('appointments.create'));

        $response->assertOk();
        $response->assertViewIs('appointment.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AppointmentController::class,
            'store',
            \App\Http\Requests\AppointmentStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $patient = Patient::factory()->create();
        $dentist = Dentist::factory()->create();
        $date = Carbon::parse(fake()->date());
        $start_time = fake()->time();
        $end_time = fake()->time();
        $status = fake()->randomElement(/** enum_attributes **/);
        $reminder_sent = fake()->boolean();
        $created_at = Carbon::parse(fake()->dateTime());
        $creator = Creator::factory()->create();
        $planned_treatment = PlannedTreatment::factory()->create();

        $response = $this->post(route('appointments.store'), [
            'patient_id' => $patient->id,
            'dentist_id' => $dentist->id,
            'date' => $date->toDateString(),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status' => $status,
            'reminder_sent' => $reminder_sent,
            'created_at' => $created_at->toDateTimeString(),
            'creator_id' => $creator->id,
            'planned_treatment_id' => $planned_treatment->id,
        ]);

        $appointments = Appointment::query()
            ->where('patient_id', $patient->id)
            ->where('dentist_id', $dentist->id)
            ->where('date', $date)
            ->where('start_time', $start_time)
            ->where('end_time', $end_time)
            ->where('status', $status)
            ->where('reminder_sent', $reminder_sent)
            ->where('created_at', $created_at)
            ->where('creator_id', $creator->id)
            ->where('planned_treatment_id', $planned_treatment->id)
            ->get();
        $this->assertCount(1, $appointments);
        $appointment = $appointments->first();

        $response->assertRedirect(route('appointments.index'));
        $response->assertSessionHas('appointment.id', $appointment->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $appointment = Appointment::factory()->create();

        $response = $this->get(route('appointments.show', $appointment));

        $response->assertOk();
        $response->assertViewIs('appointment.show');
        $response->assertViewHas('appointment');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $appointment = Appointment::factory()->create();

        $response = $this->get(route('appointments.edit', $appointment));

        $response->assertOk();
        $response->assertViewIs('appointment.edit');
        $response->assertViewHas('appointment');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\AppointmentController::class,
            'update',
            \App\Http\Requests\AppointmentUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $appointment = Appointment::factory()->create();
        $patient = Patient::factory()->create();
        $dentist = Dentist::factory()->create();
        $date = Carbon::parse(fake()->date());
        $start_time = fake()->time();
        $end_time = fake()->time();
        $status = fake()->randomElement(/** enum_attributes **/);
        $reminder_sent = fake()->boolean();
        $created_at = Carbon::parse(fake()->dateTime());
        $creator = Creator::factory()->create();
        $planned_treatment = PlannedTreatment::factory()->create();

        $response = $this->put(route('appointments.update', $appointment), [
            'patient_id' => $patient->id,
            'dentist_id' => $dentist->id,
            'date' => $date->toDateString(),
            'start_time' => $start_time,
            'end_time' => $end_time,
            'status' => $status,
            'reminder_sent' => $reminder_sent,
            'created_at' => $created_at->toDateTimeString(),
            'creator_id' => $creator->id,
            'planned_treatment_id' => $planned_treatment->id,
        ]);

        $appointment->refresh();

        $response->assertRedirect(route('appointments.index'));
        $response->assertSessionHas('appointment.id', $appointment->id);

        $this->assertEquals($patient->id, $appointment->patient_id);
        $this->assertEquals($dentist->id, $appointment->dentist_id);
        $this->assertEquals($date, $appointment->date);
        $this->assertEquals($start_time, $appointment->start_time);
        $this->assertEquals($end_time, $appointment->end_time);
        $this->assertEquals($status, $appointment->status);
        $this->assertEquals($reminder_sent, $appointment->reminder_sent);
        $this->assertEquals($created_at, $appointment->created_at);
        $this->assertEquals($creator->id, $appointment->creator_id);
        $this->assertEquals($planned_treatment->id, $appointment->planned_treatment_id);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $appointment = Appointment::factory()->create();

        $response = $this->delete(route('appointments.destroy', $appointment));

        $response->assertRedirect(route('appointments.index'));

        $this->assertModelMissing($appointment);
    }
}
