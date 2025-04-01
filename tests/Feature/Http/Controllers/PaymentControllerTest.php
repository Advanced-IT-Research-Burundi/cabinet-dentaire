<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Invoice;
use App\Models\Operator;
use App\Models\Patient;
use App\Models\Payment;
use App\Models\PaymentMethod;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use JMac\Testing\Traits\AdditionalAssertions;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\PaymentController
 */
final class PaymentControllerTest extends TestCase
{
    use AdditionalAssertions, RefreshDatabase, WithFaker;

    #[Test]
    public function index_displays_view(): void
    {
        $payments = Payment::factory()->count(3)->create();

        $response = $this->get(route('payments.index'));

        $response->assertOk();
        $response->assertViewIs('payment.index');
        $response->assertViewHas('payments');
    }


    #[Test]
    public function create_displays_view(): void
    {
        $response = $this->get(route('payments.create'));

        $response->assertOk();
        $response->assertViewIs('payment.create');
    }


    #[Test]
    public function store_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PaymentController::class,
            'store',
            \App\Http\Requests\PaymentStoreRequest::class
        );
    }

    #[Test]
    public function store_saves_and_redirects(): void
    {
        $invoice = Invoice::factory()->create();
        $patient = Patient::factory()->create();
        $payment_method = PaymentMethod::factory()->create();
        $payment_date = Carbon::parse(fake()->dateTime());
        $status = fake()->randomElement(/** enum_attributes **/);
        $operator = Operator::factory()->create();
        $created_at = Carbon::parse(fake()->dateTime());

        $response = $this->post(route('payments.store'), [
            'invoice_id' => $invoice->id,
            'patient_id' => $patient->id,
            'payment_method_id' => $payment_method->id,
            'payment_date' => $payment_date->toDateTimeString(),
            'status' => $status,
            'operator_id' => $operator->id,
            'created_at' => $created_at->toDateTimeString(),
        ]);

        $payments = Payment::query()
            ->where('invoice_id', $invoice->id)
            ->where('patient_id', $patient->id)
            ->where('payment_method_id', $payment_method->id)
            ->where('payment_date', $payment_date)
            ->where('status', $status)
            ->where('operator_id', $operator->id)
            ->where('created_at', $created_at)
            ->get();
        $this->assertCount(1, $payments);
        $payment = $payments->first();

        $response->assertRedirect(route('payments.index'));
        $response->assertSessionHas('payment.id', $payment->id);
    }


    #[Test]
    public function show_displays_view(): void
    {
        $payment = Payment::factory()->create();

        $response = $this->get(route('payments.show', $payment));

        $response->assertOk();
        $response->assertViewIs('payment.show');
        $response->assertViewHas('payment');
    }


    #[Test]
    public function edit_displays_view(): void
    {
        $payment = Payment::factory()->create();

        $response = $this->get(route('payments.edit', $payment));

        $response->assertOk();
        $response->assertViewIs('payment.edit');
        $response->assertViewHas('payment');
    }


    #[Test]
    public function update_uses_form_request_validation(): void
    {
        $this->assertActionUsesFormRequest(
            \App\Http\Controllers\PaymentController::class,
            'update',
            \App\Http\Requests\PaymentUpdateRequest::class
        );
    }

    #[Test]
    public function update_redirects(): void
    {
        $payment = Payment::factory()->create();
        $invoice = Invoice::factory()->create();
        $patient = Patient::factory()->create();
        $payment_method = PaymentMethod::factory()->create();
        $payment_date = Carbon::parse(fake()->dateTime());
        $status = fake()->randomElement(/** enum_attributes **/);
        $operator = Operator::factory()->create();
        $created_at = Carbon::parse(fake()->dateTime());

        $response = $this->put(route('payments.update', $payment), [
            'invoice_id' => $invoice->id,
            'patient_id' => $patient->id,
            'payment_method_id' => $payment_method->id,
            'payment_date' => $payment_date->toDateTimeString(),
            'status' => $status,
            'operator_id' => $operator->id,
            'created_at' => $created_at->toDateTimeString(),
        ]);

        $payment->refresh();

        $response->assertRedirect(route('payments.index'));
        $response->assertSessionHas('payment.id', $payment->id);

        $this->assertEquals($invoice->id, $payment->invoice_id);
        $this->assertEquals($patient->id, $payment->patient_id);
        $this->assertEquals($payment_method->id, $payment->payment_method_id);
        $this->assertEquals($payment_date, $payment->payment_date);
        $this->assertEquals($status, $payment->status);
        $this->assertEquals($operator->id, $payment->operator_id);
        $this->assertEquals($created_at, $payment->created_at);
    }


    #[Test]
    public function destroy_deletes_and_redirects(): void
    {
        $payment = Payment::factory()->create();

        $response = $this->delete(route('payments.destroy', $payment));

        $response->assertRedirect(route('payments.index'));

        $this->assertModelMissing($payment);
    }
}
