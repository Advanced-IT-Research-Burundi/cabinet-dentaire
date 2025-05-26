<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaisseStoreRequest;
use App\Http\Requests\CaisseUpdateRequest;
use App\Models\Caisse;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaisseController extends Controller
{
    public function index(Request $request)
    {
        $query = Caisse::with('user');
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->search . '%')
                ->orWhere('description', 'LIKE', '%' . $request->search . '%');
            });
        }

        $caisses = $query->latest('date')->paginate(10)->withQueryString();

        $totalCaisses = Caisse::count();
        $montantTotal = Caisse::sum('montant');
        $caissesActives = Caisse::where('status', 'active')->count();
        $caissesEnAttente = Caisse::where('status', 'pending')->count();

        return view('caisse.index', [
            'caisses' => $caisses,
            'totalCaisses' => $totalCaisses,
            'montantTotal' => $montantTotal,
            'caissesActives' => $caissesActives,
            'caissesEnAttente' => $caissesEnAttente,
        ]);
    }

    public function create(Request $request)
    {
        $users = User::all();
        return view('caisse.create', [
            'users' => $users,
        ]);
    }

    public function store(CaisseStoreRequest $request)
    {

        $caisse = Caisse::create($request->validated());

        $request->session()->flash('caisse.id', $caisse->id);

        return redirect()->route('caisses.index');
    }

    public function show(Request $request,  $caisse)
    {
        $currentCaisse = Caisse::with(['caisseDetails'])->find($caisse);

        return view('caisse.show', [
            'caisse' => $currentCaisse,
        ]);
    }

    public function edit(Request $request, Caisse $caisse)
    {
        return view('caisse.edit', [
            'caisse' => $caisse,
        ]);
    }

    public function update(CaisseUpdateRequest $request, Caisse $caisse)
    {
        $caisse->update($request->validated());

        $request->session()->flash('caisse.id', $caisse->id);

        return redirect()->route('caisses.index');
    }

    public function destroy(Request $request, Caisse $caisse)
    {
        $caisse->delete();

        return redirect()->route('caisses.index');
    }
}
