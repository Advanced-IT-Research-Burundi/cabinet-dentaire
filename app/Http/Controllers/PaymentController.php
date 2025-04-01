<?php

namespace App\Http\Controllers;

use App\Http\Requests\PaymentStoreRequest;
use App\Http\Requests\PaymentUpdateRequest;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): Response
    {
        $payments = Payment::all();

        return view('payment.index', [
            'payments' => $payments,
        ]);
    }

    public function create(Request $request): Response
    {
        return view('payment.create');
    }

    public function store(PaymentStoreRequest $request): Response
    {
        $payment = Payment::create($request->validated());

        $request->session()->flash('payment.id', $payment->id);

        return redirect()->route('payments.index');
    }

    public function show(Request $request, Payment $payment): Response
    {
        return view('payment.show', [
            'payment' => $payment,
        ]);
    }

    public function edit(Request $request, Payment $payment): Response
    {
        return view('payment.edit', [
            'payment' => $payment,
        ]);
    }

    public function update(PaymentUpdateRequest $request, Payment $payment): Response
    {
        $payment->update($request->validated());

        $request->session()->flash('payment.id', $payment->id);

        return redirect()->route('payments.index');
    }

    public function destroy(Request $request, Payment $payment): Response
    {
        $payment->delete();

        return redirect()->route('payments.index');
    }
}
