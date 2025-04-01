<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Dentist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\DentistController
 */
final class DentistControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $dentists = Dentist::factory()->count(3)->create();

        $response = $this->get(route('dentists.index'));

        $response->assertOk();
        $response->assertViewIs('dentist.index');
        $response->assertViewHas('dentists');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('dentists.create'));

        $response->assertOk();
        $response->assertViewIs('dentist.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\DentistController::class,
            'store',
            \App\Http\Requests\DentistStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $user = User::factory()->create();
        $available = fake()->boolean();

        $response = $this->post(route('dentists.store'), [
            'user_id' => $user->id,
            'available' => $available,
        ]);

        $dentists = Dentist::query()
            ->where('user_id', $user->id)
            ->where('available', $available)
            ->get();
        $this->assertCount(1, $dentists);
        $dentist = $dentists->first();

        $response->assertRedirect(route('dentists.index'));
        $response->assertSessionHas('dentist.id', $dentist->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $dentist = Dentist::factory()->create();

        $response = $this->get(route('dentists.show', $dentist));

        $response->assertOk();
        $response->assertViewIs('dentist.show');
        $response->assertViewHas('dentist');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $dentist = Dentist::factory()->create();

        $response = $this->get(route('dentists.edit', $dentist));

        $response->assertOk();
        $response->assertViewIs('dentist.edit');
        $response->assertViewHas('dentist');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\DentistController::class,
            'update',
            \App\Http\Requests\DentistUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $dentist = Dentist::factory()->create();
        $user = User::factory()->create();
        $available = fake()->boolean();

        $response = $this->put(route('dentists.update', $dentist), [
            'user_id' => $user->id,
            'available' => $available,
        ]);

        $dentist->refresh();

        $response->assertRedirect(route('dentists.index'));
        $response->assertSessionHas('dentist.id', $dentist->id);

        $this->assertEquals($user->id, $dentist->user_id);
        $this->assertEquals($available, $dentist->available);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $dentist = Dentist::factory()->create();

        $response = $this->delete(route('dentists.destroy', $dentist));

        $response->assertRedirect(route('dentists.index'));

        $this->assertModelMissing($dentist);
    }
}
