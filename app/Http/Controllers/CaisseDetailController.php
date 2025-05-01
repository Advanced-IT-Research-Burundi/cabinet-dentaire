<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaisseDetailStoreRequest;
use App\Http\Requests\CaisseDetailUpdateRequest;
use App\Models\CaisseDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaisseDetailController extends Controller
{
    public function index(Request $request): Response
    {
        $caisseDetails = CaisseDetail::all();

        return view('caisseDetail.index', [
            'caisseDetails' => $caisseDetails,
        ]);
    }

    public function create(Request $request): Response
    {
        return view('caisseDetail.create');
    }

    public function store(CaisseDetailStoreRequest $request): Response
    {
        $caisseDetail = CaisseDetail::create($request->validated());

        $request->session()->flash('caisseDetail.id', $caisseDetail->id);

        return redirect()->route('caisseDetails.index');
    }

    public function show(Request $request, CaisseDetail $caisseDetail): Response
    {
        return view('caisseDetail.show', [
            'caisseDetail' => $caisseDetail,
        ]);
    }

    public function edit(Request $request, CaisseDetail $caisseDetail): Response
    {
        return view('caisseDetail.edit', [
            'caisseDetail' => $caisseDetail,
        ]);
    }

    public function update(CaisseDetailUpdateRequest $request, CaisseDetail $caisseDetail): Response
    {
        $caisseDetail->update($request->validated());

        $request->session()->flash('caisseDetail.id', $caisseDetail->id);

        return redirect()->route('caisseDetails.index');
    }

    public function destroy(Request $request, CaisseDetail $caisseDetail): Response
    {
        $caisseDetail->delete();

        return redirect()->route('caisseDetails.index');
    }
}
