<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Caisse;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CaisseController
 */
final class CaisseControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $caisses = Caisse::factory()->count(3)->create();

        $response = $this->get(route('caisses.index'));

        $response->assertOk();
        $response->assertViewIs('caisse.index');
        $response->assertViewHas('caisses');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('caisses.create'));

        $response->assertOk();
        $response->assertViewIs('caisse.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CaisseController::class,
            'store',
            \App\Http\Requests\CaisseStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $date = Carbon::parse(fake()->dateTime());
        $montant = fake()->randomFloat(/** double_attributes **/);
        $user = User::factory()->create();

        $response = $this->post(route('caisses.store'), [
            'date' => $date->toDateTimeString(),
            'montant' => $montant,
            'user_id' => $user->id,
        ]);

        $caisses = Caisse::query()
            ->where('date', $date)
            ->where('montant', $montant)
            ->where('user_id', $user->id)
            ->get();
        $this->assertCount(1, $caisses);
        $caisse = $caisses->first();

        $response->assertRedirect(route('caisses.index'));
        $response->assertSessionHas('caisse.id', $caisse->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $caisse = Caisse::factory()->create();

        $response = $this->get(route('caisses.show', $caisse));

        $response->assertOk();
        $response->assertViewIs('caisse.show');
        $response->assertViewHas('caisse');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $caisse = Caisse::factory()->create();

        $response = $this->get(route('caisses.edit', $caisse));

        $response->assertOk();
        $response->assertViewIs('caisse.edit');
        $response->assertViewHas('caisse');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CaisseController::class,
            'update',
            \App\Http\Requests\CaisseUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $caisse = Caisse::factory()->create();
        $date = Carbon::parse(fake()->dateTime());
        $montant = fake()->randomFloat(/** double_attributes **/);
        $user = User::factory()->create();

        $response = $this->put(route('caisses.update', $caisse), [
            'date' => $date->toDateTimeString(),
            'montant' => $montant,
            'user_id' => $user->id,
        ]);

        $caisse->refresh();

        $response->assertRedirect(route('caisses.index'));
        $response->assertSessionHas('caisse.id', $caisse->id);

        $this->assertEquals($date->timestamp, $caisse->date);
        $this->assertEquals($montant, $caisse->montant);
        $this->assertEquals($user->id, $caisse->user_id);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $caisse = Caisse::factory()->create();

        $response = $this->delete(route('caisses.destroy', $caisse));

        $response->assertRedirect(route('caisses.index'));

        $this->assertModelMissing($caisse);
    }
}
