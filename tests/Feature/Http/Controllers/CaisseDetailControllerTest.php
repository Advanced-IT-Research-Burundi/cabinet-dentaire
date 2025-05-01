<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Caisse;
use App\Models\CaisseDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CaisseDetailController
 */
final class CaisseDetailControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $caisseDetails = CaisseDetail::factory()->count(3)->create();

        $response = $this->get(route('caisse-details.index'));

        $response->assertOk();
        $response->assertViewIs('caisseDetail.index');
        $response->assertViewHas('caisseDetails');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('caisse-details.create'));

        $response->assertOk();
        $response->assertViewIs('caisseDetail.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CaisseDetailController::class,
            'store',
            \App\Http\Requests\CaisseDetailStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $caisse = Caisse::factory()->create();
        $price = fake()->randomFloat(/** double_attributes **/);
        $total = fake()->randomFloat(/** double_attributes **/);
        $user = User::factory()->create();

        $response = $this->post(route('caisse-details.store'), [
            'caisse_id' => $caisse->id,
            'price' => $price,
            'total' => $total,
            'user_id' => $user->id,
        ]);

        $caisseDetails = CaisseDetail::query()
            ->where('caisse_id', $caisse->id)
            ->where('price', $price)
            ->where('total', $total)
            ->where('user_id', $user->id)
            ->get();
        $this->assertCount(1, $caisseDetails);
        $caisseDetail = $caisseDetails->first();

        $response->assertRedirect(route('caisseDetails.index'));
        $response->assertSessionHas('caisseDetail.id', $caisseDetail->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $caisseDetail = CaisseDetail::factory()->create();

        $response = $this->get(route('caisse-details.show', $caisseDetail));

        $response->assertOk();
        $response->assertViewIs('caisseDetail.show');
        $response->assertViewHas('caisseDetail');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $caisseDetail = CaisseDetail::factory()->create();

        $response = $this->get(route('caisse-details.edit', $caisseDetail));

        $response->assertOk();
        $response->assertViewIs('caisseDetail.edit');
        $response->assertViewHas('caisseDetail');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CaisseDetailController::class,
            'update',
            \App\Http\Requests\CaisseDetailUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $caisseDetail = CaisseDetail::factory()->create();
        $caisse = Caisse::factory()->create();
        $price = fake()->randomFloat(/** double_attributes **/);
        $total = fake()->randomFloat(/** double_attributes **/);
        $user = User::factory()->create();

        $response = $this->put(route('caisse-details.update', $caisseDetail), [
            'caisse_id' => $caisse->id,
            'price' => $price,
            'total' => $total,
            'user_id' => $user->id,
        ]);

        $caisseDetail->refresh();

        $response->assertRedirect(route('caisseDetails.index'));
        $response->assertSessionHas('caisseDetail.id', $caisseDetail->id);

        $this->assertEquals($caisse->id, $caisseDetail->caisse_id);
        $this->assertEquals($price, $caisseDetail->price);
        $this->assertEquals($total, $caisseDetail->total);
        $this->assertEquals($user->id, $caisseDetail->user_id);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $caisseDetail = CaisseDetail::factory()->create();

        $response = $this->delete(route('caisse-details.destroy', $caisseDetail));

        $response->assertRedirect(route('caisseDetails.index'));

        $this->assertModelMissing($caisseDetail);
    }
}
