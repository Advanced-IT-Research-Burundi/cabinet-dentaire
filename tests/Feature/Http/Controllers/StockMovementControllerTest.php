<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Stock;
use App\Models\StockMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\StockMovementController
 */
final class StockMovementControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $stockMovements = StockMovement::factory()->count(3)->create();

        $response = $this->get(route('stock-movements.index'));

        $response->assertOk();
        $response->assertViewIs('stockMovement.index');
        $response->assertViewHas('stockMovements');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('stock-movements.create'));

        $response->assertOk();
        $response->assertViewIs('stockMovement.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\StockMovementController::class,
            'store',
            \App\Http\Requests\StockMovementStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $stock = Stock::factory()->create();
        $type = fake()->word();
        $date = Carbon::parse(fake()->dateTime());
        $quantity = fake()->randomFloat(/** float_attributes **/);
        $price = fake()->randomFloat(/** double_attributes **/);

        $response = $this->post(route('stock-movements.store'), [
            'stock_id' => $stock->id,
            'type' => $type,
            'date' => $date->toDateTimeString(),
            'quantity' => $quantity,
            'price' => $price,
        ]);

        $stockMovements = StockMovement::query()
            ->where('stock_id', $stock->id)
            ->where('type', $type)
            ->where('date', $date)
            ->where('quantity', $quantity)
            ->where('price', $price)
            ->get();
        $this->assertCount(1, $stockMovements);
        $stockMovement = $stockMovements->first();

        $response->assertRedirect(route('stockMovements.index'));
        $response->assertSessionHas('stockMovement.id', $stockMovement->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $stockMovement = StockMovement::factory()->create();

        $response = $this->get(route('stock-movements.show', $stockMovement));

        $response->assertOk();
        $response->assertViewIs('stockMovement.show');
        $response->assertViewHas('stockMovement');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $stockMovement = StockMovement::factory()->create();

        $response = $this->get(route('stock-movements.edit', $stockMovement));

        $response->assertOk();
        $response->assertViewIs('stockMovement.edit');
        $response->assertViewHas('stockMovement');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\StockMovementController::class,
            'update',
            \App\Http\Requests\StockMovementUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $stockMovement = StockMovement::factory()->create();
        $stock = Stock::factory()->create();
        $type = fake()->word();
        $date = Carbon::parse(fake()->dateTime());
        $quantity = fake()->randomFloat(/** float_attributes **/);
        $price = fake()->randomFloat(/** double_attributes **/);

        $response = $this->put(route('stock-movements.update', $stockMovement), [
            'stock_id' => $stock->id,
            'type' => $type,
            'date' => $date->toDateTimeString(),
            'quantity' => $quantity,
            'price' => $price,
        ]);

        $stockMovement->refresh();

        $response->assertRedirect(route('stockMovements.index'));
        $response->assertSessionHas('stockMovement.id', $stockMovement->id);

        $this->assertEquals($stock->id, $stockMovement->stock_id);
        $this->assertEquals($type, $stockMovement->type);
        $this->assertEquals($date->timestamp, $stockMovement->date);
        $this->assertEquals($quantity, $stockMovement->quantity);
        $this->assertEquals($price, $stockMovement->price);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $stockMovement = StockMovement::factory()->create();

        $response = $this->delete(route('stock-movements.destroy', $stockMovement));

        $response->assertRedirect(route('stockMovements.index'));

        $this->assertModelMissing($stockMovement);
    }
}
