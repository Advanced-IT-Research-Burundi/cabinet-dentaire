<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Company;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\CompanyController
 */
final class CompanyControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $companies = Company::factory()->count(3)->create();

        $response = $this->get(route('companies.index'));

        $response->assertOk();
        $response->assertViewIs('company.index');
        $response->assertViewHas('companies');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('companies.create'));

        $response->assertOk();
        $response->assertViewIs('company.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CompanyController::class,
            'store',
            \App\Http\Requests\CompanyStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $is_actif = fake()->boolean();
        $user_id = fake()->numberBetween(-10000, 10000);
        $created_at = Carbon::parse(fake()->dateTime());
        $updated_at = Carbon::parse(fake()->dateTime());
        $deleted_at = Carbon::parse(fake()->dateTime());

        $response = $this->post(route('companies.store'), [
            'is_actif' => $is_actif,
            'user_id' => $user_id,
            'created_at' => $created_at->toDateTimeString(),
            'updated_at' => $updated_at->toDateTimeString(),
            'deleted_at' => $deleted_at->toDateTimeString(),
        ]);

        $companies = Company::query()
            ->where('is_actif', $is_actif)
            ->where('user_id', $user_id)
            ->where('created_at', $created_at)
            ->where('updated_at', $updated_at)
            ->where('deleted_at', $deleted_at)
            ->get();
        $this->assertCount(1, $companies);
        $company = $companies->first();

        $response->assertRedirect(route('companies.index'));
        $response->assertSessionHas('company.id', $company->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $company = Company::factory()->create();

        $response = $this->get(route('companies.show', $company));

        $response->assertOk();
        $response->assertViewIs('company.show');
        $response->assertViewHas('company');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $company = Company::factory()->create();

        $response = $this->get(route('companies.edit', $company));

        $response->assertOk();
        $response->assertViewIs('company.edit');
        $response->assertViewHas('company');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\CompanyController::class,
            'update',
            \App\Http\Requests\CompanyUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $company = Company::factory()->create();
        $is_actif = fake()->boolean();
        $user_id = fake()->numberBetween(-10000, 10000);
        $created_at = Carbon::parse(fake()->dateTime());
        $updated_at = Carbon::parse(fake()->dateTime());
        $deleted_at = Carbon::parse(fake()->dateTime());

        $response = $this->put(route('companies.update', $company), [
            'is_actif' => $is_actif,
            'user_id' => $user_id,
            'created_at' => $created_at->toDateTimeString(),
            'updated_at' => $updated_at->toDateTimeString(),
            'deleted_at' => $deleted_at->toDateTimeString(),
        ]);

        $company->refresh();

        $response->assertRedirect(route('companies.index'));
        $response->assertSessionHas('company.id', $company->id);

        $this->assertEquals($is_actif, $company->is_actif);
        $this->assertEquals($user_id, $company->user_id);
        $this->assertEquals($created_at->timestamp, $company->created_at);
        $this->assertEquals($updated_at->timestamp, $company->updated_at);
        $this->assertEquals($deleted_at->timestamp, $company->deleted_at);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $company = Company::factory()->create();

        $response = $this->delete(route('companies.destroy', $company));

        $response->assertRedirect(route('companies.index'));

        $this->assertModelMissing($company);
    }
}
