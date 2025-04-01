<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Creator;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PatientController
 */
final class PatientControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $patients = Patient::factory()->count(3)->create();

        $response = $this->get(route('patients.index'));

        $response->assertOk();
        $response->assertViewIs('patient.index');
        $response->assertViewHas('patients');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('patients.create'));

        $response->assertOk();
        $response->assertViewIs('patient.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PatientController::class,
            'store',
            \App\Http\Requests\PatientStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $first_name = fake()->firstName();
        $birth_date = Carbon::parse(fake()->date());
        $gender = fake()->randomElement(/** enum_attributes **/);
        $creator = Creator::factory()->create();

        $response = $this->post(route('patients.store'), [
            'first_name' => $first_name,
            'birth_date' => $birth_date->toDateString(),
            'gender' => $gender,
            'creator_id' => $creator->id,
        ]);

        $patients = Patient::query()
            ->where('first_name', $first_name)
            ->where('birth_date', $birth_date)
            ->where('gender', $gender)
            ->where('creator_id', $creator->id)
            ->get();
        $this->assertCount(1, $patients);
        $patient = $patients->first();

        $response->assertRedirect(route('patients.index'));
        $response->assertSessionHas('patient.id', $patient->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->get(route('patients.show', $patient));

        $response->assertOk();
        $response->assertViewIs('patient.show');
        $response->assertViewHas('patient');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->get(route('patients.edit', $patient));

        $response->assertOk();
        $response->assertViewIs('patient.edit');
        $response->assertViewHas('patient');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PatientController::class,
            'update',
            \App\Http\Requests\PatientUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $patient = Patient::factory()->create();
        $first_name = fake()->firstName();
        $birth_date = Carbon::parse(fake()->date());
        $gender = fake()->randomElement(/** enum_attributes **/);
        $creator = Creator::factory()->create();

        $response = $this->put(route('patients.update', $patient), [
            'first_name' => $first_name,
            'birth_date' => $birth_date->toDateString(),
            'gender' => $gender,
            'creator_id' => $creator->id,
        ]);

        $patient->refresh();

        $response->assertRedirect(route('patients.index'));
        $response->assertSessionHas('patient.id', $patient->id);

        $this->assertEquals($first_name, $patient->first_name);
        $this->assertEquals($birth_date, $patient->birth_date);
        $this->assertEquals($gender, $patient->gender);
        $this->assertEquals($creator->id, $patient->creator_id);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $patient = Patient::factory()->create();

        $response = $this->delete(route('patients.destroy', $patient));

        $response->assertRedirect(route('patients.index'));

        $this->assertModelMissing($patient);
    }
}
