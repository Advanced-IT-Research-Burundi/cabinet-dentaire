<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Appointment;
use App\Models\Dentist;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\TreatmentType;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\TreatmentController
 */
final class TreatmentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $treatments = Treatment::factory()->count(3)->create();

        $response = $this->get(route('treatments.index'));

        $response->assertOk();
        $response->assertViewIs('treatment.index');
        $response->assertViewHas('treatments');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('treatments.create'));

        $response->assertOk();
        $response->assertViewIs('treatment.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TreatmentController::class,
            'store',
            \App\Http\Requests\TreatmentStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $patient = Patient::factory()->create();
        $dentist = Dentist::factory()->create();
        $treatment_type = TreatmentType::factory()->create();
        $appointment = Appointment::factory()->create();
        $date = Carbon::parse(fake()->date());
        $status = fake()->randomElement(/** enum_attributes **/);
        $created_at = Carbon::parse(fake()->dateTime());
        $updated_at = Carbon::parse(fake()->dateTime());

        $response = $this->post(route('treatments.store'), [
            'patient_id' => $patient->id,
            'dentist_id' => $dentist->id,
            'treatment_type_id' => $treatment_type->id,
            'appointment_id' => $appointment->id,
            'date' => $date->toDateString(),
            'status' => $status,
            'created_at' => $created_at->toDateTimeString(),
            'updated_at' => $updated_at->toDateTimeString(),
        ]);

        $treatments = Treatment::query()
            ->where('patient_id', $patient->id)
            ->where('dentist_id', $dentist->id)
            ->where('treatment_type_id', $treatment_type->id)
            ->where('appointment_id', $appointment->id)
            ->where('date', $date)
            ->where('status', $status)
            ->where('created_at', $created_at)
            ->where('updated_at', $updated_at)
            ->get();
        $this->assertCount(1, $treatments);
        $treatment = $treatments->first();

        $response->assertRedirect(route('treatments.index'));
        $response->assertSessionHas('treatment.id', $treatment->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $treatment = Treatment::factory()->create();

        $response = $this->get(route('treatments.show', $treatment));

        $response->assertOk();
        $response->assertViewIs('treatment.show');
        $response->assertViewHas('treatment');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $treatment = Treatment::factory()->create();

        $response = $this->get(route('treatments.edit', $treatment));

        $response->assertOk();
        $response->assertViewIs('treatment.edit');
        $response->assertViewHas('treatment');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\TreatmentController::class,
            'update',
            \App\Http\Requests\TreatmentUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $treatment = Treatment::factory()->create();
        $patient = Patient::factory()->create();
        $dentist = Dentist::factory()->create();
        $treatment_type = TreatmentType::factory()->create();
        $appointment = Appointment::factory()->create();
        $date = Carbon::parse(fake()->date());
        $status = fake()->randomElement(/** enum_attributes **/);
        $created_at = Carbon::parse(fake()->dateTime());
        $updated_at = Carbon::parse(fake()->dateTime());

        $response = $this->put(route('treatments.update', $treatment), [
            'patient_id' => $patient->id,
            'dentist_id' => $dentist->id,
            'treatment_type_id' => $treatment_type->id,
            'appointment_id' => $appointment->id,
            'date' => $date->toDateString(),
            'status' => $status,
            'created_at' => $created_at->toDateTimeString(),
            'updated_at' => $updated_at->toDateTimeString(),
        ]);

        $treatment->refresh();

        $response->assertRedirect(route('treatments.index'));
        $response->assertSessionHas('treatment.id', $treatment->id);

        $this->assertEquals($patient->id, $treatment->patient_id);
        $this->assertEquals($dentist->id, $treatment->dentist_id);
        $this->assertEquals($treatment_type->id, $treatment->treatment_type_id);
        $this->assertEquals($appointment->id, $treatment->appointment_id);
        $this->assertEquals($date, $treatment->date);
        $this->assertEquals($status, $treatment->status);
        $this->assertEquals($created_at, $treatment->created_at);
        $this->assertEquals($updated_at, $treatment->updated_at);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $treatment = Treatment::factory()->create();

        $response = $this->delete(route('treatments.destroy', $treatment));

        $response->assertRedirect(route('treatments.index'));

        $this->assertModelMissing($treatment);
    }
}
