<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\ObrPointer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\ObrPointerController
 */
final class ObrPointerControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $obrPointers = ObrPointer::factory()->count(3)->create();

        $response = $this->get(route('obr-pointers.index'));

        $response->assertOk();
        $response->assertViewIs('obrPointer.index');
        $response->assertViewHas('obrPointers');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('obr-pointers.create'));

        $response->assertOk();
        $response->assertViewIs('obrPointer.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ObrPointerController::class,
            'store',
            \App\Http\Requests\ObrPointerStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $response = $this->post(route('obr-pointers.store'));

        $response->assertRedirect(route('obrPointers.index'));
        $response->assertSessionHas('obrPointer.id', $obrPointer->id);

        $this->assertDatabaseHas(obrPointers, [ /* ... */ ]);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $obrPointer = ObrPointer::factory()->create();

        $response = $this->get(route('obr-pointers.show', $obrPointer));

        $response->assertOk();
        $response->assertViewIs('obrPointer.show');
        $response->assertViewHas('obrPointer');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $obrPointer = ObrPointer::factory()->create();

        $response = $this->get(route('obr-pointers.edit', $obrPointer));

        $response->assertOk();
        $response->assertViewIs('obrPointer.edit');
        $response->assertViewHas('obrPointer');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\ObrPointerController::class,
            'update',
            \App\Http\Requests\ObrPointerUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $obrPointer = ObrPointer::factory()->create();

        $response = $this->put(route('obr-pointers.update', $obrPointer));

        $obrPointer->refresh();

        $response->assertRedirect(route('obrPointers.index'));
        $response->assertSessionHas('obrPointer.id', $obrPointer->id);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $obrPointer = ObrPointer::factory()->create();

        $response = $this->delete(route('obr-pointers.destroy', $obrPointer));

        $response->assertRedirect(route('obrPointers.index'));

        $this->assertModelMissing($obrPointer);
    }
}
