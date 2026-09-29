<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaisseStoreRequest;
use App\Http\Requests\CaisseUpdateRequest;
use App\Models\Caisse;
use App\Models\User;
use App\Models\CaisseDetail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CaisseController extends Controller
{
    public function index(Request $request)
    {
        $query = Caisse::utilisateurs()->with('user');
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

        $totalCaisses = Caisse::utilisateurs()->count();
        $montantTotal = Caisse::utilisateurs()->sum('montant');
        $caissesActives = Caisse::utilisateurs()->where('status', 'active')->count();
        $caissePrincipale = Caisse::centrale()->montant;

        return view('caisse.index', [
            'caisses' => $caisses,
            'totalCaisses' => $totalCaisses,
            'montantTotal' => $montantTotal,
            'caissesActives' => $caissesActives,
            'caissePrincipale' => $caissePrincipale,
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

        $validated = $request->validated();

        // if ($validated['montant'] <= 0) {
        //     return back()->withErrors([
        //         'error' => 'Le montant doit etre supérieur à 0.'
        //     ])->withInput();
        // }
        \DB::beginTransaction();


        $caisse = Caisse::create($request->validated());
        CaisseDetail::create([
                'caisse_id' => $caisse->id,
                'type' => "MONTANT RETRAIT",
                'price' => 0,
                'total' => 0,
                'status' => '1',
                'user_id' => auth()->user()->id,
                'description' => $validated['description'],
        ]);
        \DB::commit();
        return redirect()->route('caisses.index')
                ->with('success', 'La caisse a été créée avec succès.');
    }

    public function show(Request $request,  $caisse)
    {
        $currentCaisse = Caisse::with(['caisseDetails'])->findOrFail($caisse);

        if ($currentCaisse->is_centrale) {
            return redirect()->route('caisse-centrale.index');
        }

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
        abort_if($caisse->is_centrale, 403, 'La caisse centrale ne peut pas être supprimée.');

        $caisse->delete();

        return redirect()->route('caisses.index');
    }

}
