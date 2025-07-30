<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\ObrRequestBody;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ObrRequestBodyController
 */
final class ObrRequestBodyControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $obrRequestBodies = ObrRequestBody::factory()->count(3)->create();

        $response = $this->get(route('obr-request-bodies.index'));

        $response->assertOk();
        $response->assertViewIs('obrRequestBody.index');
        $response->assertViewHas('obrRequestBodies');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('obr-request-bodies.create'));

        $response->assertOk();
        $response->assertViewIs('obrRequestBody.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ObrRequestBodyController::class,
            'store',
            \App\Http\Requests\ObrRequestBodyStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $response = $this->post(route('obr-request-bodies.store'));

        $response->assertRedirect(route('obrRequestBodies.index'));
        $response->assertSessionHas('obrRequestBody.id', $obrRequestBody->id);

        $this->assertDatabaseHas(obrRequestBodies, [ /* ... */ ]);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $obrRequestBody = ObrRequestBody::factory()->create();

        $response = $this->get(route('obr-request-bodies.show', $obrRequestBody));

        $response->assertOk();
        $response->assertViewIs('obrRequestBody.show');
        $response->assertViewHas('obrRequestBody');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $obrRequestBody = ObrRequestBody::factory()->create();

        $response = $this->get(route('obr-request-bodies.edit', $obrRequestBody));

        $response->assertOk();
        $response->assertViewIs('obrRequestBody.edit');
        $response->assertViewHas('obrRequestBody');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ObrRequestBodyController::class,
            'update',
            \App\Http\Requests\ObrRequestBodyUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $obrRequestBody = ObrRequestBody::factory()->create();

        $response = $this->put(route('obr-request-bodies.update', $obrRequestBody));

        $obrRequestBody->refresh();

        $response->assertRedirect(route('obrRequestBodies.index'));
        $response->assertSessionHas('obrRequestBody.id', $obrRequestBody->id);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $obrRequestBody = ObrRequestBody::factory()->create();

        $response = $this->delete(route('obr-request-bodies.destroy', $obrRequestBody));

        $response->assertRedirect(route('obrRequestBodies.index'));

        $this->assertModelMissing($obrRequestBody);
    }
}
