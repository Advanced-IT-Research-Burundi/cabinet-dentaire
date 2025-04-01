<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Creator;
use App\Models\Invoice;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\InvoiceController
 */
final class InvoiceControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $invoices = Invoice::factory()->count(3)->create();

        $response = $this->get(route('invoices.index'));

        $response->assertOk();
        $response->assertViewIs('invoice.index');
        $response->assertViewHas('invoices');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('invoices.create'));

        $response->assertOk();
        $response->assertViewIs('invoice.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\InvoiceController::class,
            'store',
            \App\Http\Requests\InvoiceStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $patient = Patient::factory()->create();
        $invoice_number = fake()->word();
        $issue_date = Carbon::parse(fake()->date());
        $due_date = Carbon::parse(fake()->date());
        $total_amount = fake()->randomFloat(/** decimal_attributes **/);
        $insurance_amount = fake()->randomFloat(/** decimal_attributes **/);
        $patient_amount = fake()->randomFloat(/** decimal_attributes **/);
        $status = fake()->randomElement(/** enum_attributes **/);
        $notes = fake()->text();
        $creator = Creator::factory()->create();
        $created_at = Carbon::parse(fake()->dateTime());
        $updated_at = Carbon::parse(fake()->dateTime());

        $response = $this->post(route('invoices.store'), [
            'patient_id' => $patient->id,
            'invoice_number' => $invoice_number,
            'issue_date' => $issue_date->toDateString(),
            'due_date' => $due_date->toDateString(),
            'total_amount' => $total_amount,
            'insurance_amount' => $insurance_amount,
            'patient_amount' => $patient_amount,
            'status' => $status,
            'notes' => $notes,
            'creator_id' => $creator->id,
            'created_at' => $created_at->toDateTimeString(),
            'updated_at' => $updated_at->toDateTimeString(),
        ]);

        $invoices = Invoice::query()
            ->where('patient_id', $patient->id)
            ->where('invoice_number', $invoice_number)
            ->where('issue_date', $issue_date)
            ->where('due_date', $due_date)
            ->where('total_amount', $total_amount)
            ->where('insurance_amount', $insurance_amount)
            ->where('patient_amount', $patient_amount)
            ->where('status', $status)
            ->where('notes', $notes)
            ->where('creator_id', $creator->id)
            ->where('created_at', $created_at)
            ->where('updated_at', $updated_at)
            ->get();
        $this->assertCount(1, $invoices);
        $invoice = $invoices->first();

        $response->assertRedirect(route('invoices.index'));
        $response->assertSessionHas('invoice.id', $invoice->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $invoice = Invoice::factory()->create();

        $response = $this->get(route('invoices.show', $invoice));

        $response->assertOk();
        $response->assertViewIs('invoice.show');
        $response->assertViewHas('invoice');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $invoice = Invoice::factory()->create();

        $response = $this->get(route('invoices.edit', $invoice));

        $response->assertOk();
        $response->assertViewIs('invoice.edit');
        $response->assertViewHas('invoice');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\InvoiceController::class,
            'update',
            \App\Http\Requests\InvoiceUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $invoice = Invoice::factory()->create();
        $patient = Patient::factory()->create();
        $invoice_number = fake()->word();
        $issue_date = Carbon::parse(fake()->date());
        $due_date = Carbon::parse(fake()->date());
        $total_amount = fake()->randomFloat(/** decimal_attributes **/);
        $insurance_amount = fake()->randomFloat(/** decimal_attributes **/);
        $patient_amount = fake()->randomFloat(/** decimal_attributes **/);
        $status = fake()->randomElement(/** enum_attributes **/);
        $notes = fake()->text();
        $creator = Creator::factory()->create();
        $created_at = Carbon::parse(fake()->dateTime());
        $updated_at = Carbon::parse(fake()->dateTime());

        $response = $this->put(route('invoices.update', $invoice), [
            'patient_id' => $patient->id,
            'invoice_number' => $invoice_number,
            'issue_date' => $issue_date->toDateString(),
            'due_date' => $due_date->toDateString(),
            'total_amount' => $total_amount,
            'insurance_amount' => $insurance_amount,
            'patient_amount' => $patient_amount,
            'status' => $status,
            'notes' => $notes,
            'creator_id' => $creator->id,
            'created_at' => $created_at->toDateTimeString(),
            'updated_at' => $updated_at->toDateTimeString(),
        ]);

        $invoice->refresh();

        $response->assertRedirect(route('invoices.index'));
        $response->assertSessionHas('invoice.id', $invoice->id);

        $this->assertEquals($patient->id, $invoice->patient_id);
        $this->assertEquals($invoice_number, $invoice->invoice_number);
        $this->assertEquals($issue_date, $invoice->issue_date);
        $this->assertEquals($due_date, $invoice->due_date);
        $this->assertEquals($total_amount, $invoice->total_amount);
        $this->assertEquals($insurance_amount, $invoice->insurance_amount);
        $this->assertEquals($patient_amount, $invoice->patient_amount);
        $this->assertEquals($status, $invoice->status);
        $this->assertEquals($notes, $invoice->notes);
        $this->assertEquals($creator->id, $invoice->creator_id);
        $this->assertEquals($created_at, $invoice->created_at);
        $this->assertEquals($updated_at, $invoice->updated_at);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $invoice = Invoice::factory()->create();

        $response = $this->delete(route('invoices.destroy', $invoice));

        $response->assertRedirect(route('invoices.index'));

        $this->assertModelMissing($invoice);
    }
}
