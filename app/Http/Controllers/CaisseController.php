<?php

namespace App\Http\Controllers;

use App\Http\Requests\CaisseStoreRequest;
use App\Http\Requests\CaisseUpdateRequest;
use App\Models\Caisse;
use App\Models\Company;
use App\Models\User;
use App\Models\CaisseDetail;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
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
        $currentCaisse = Caisse::findOrFail($caisse);

        if ($currentCaisse->is_centrale) {
            return redirect()->route('caisse-centrale.index');
        }

        [$dateDebut, $dateFin] = $this->periode($request);

        return view('caisse.show', [
            'caisse' => $currentCaisse,
            'details' => $this->detailsPeriode($currentCaisse, $dateDebut, $dateFin),
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
        ]);
    }

    public function print(Request $request, Caisse $caisse)
    {
        abort_if($caisse->is_centrale, 404);

        [$dateDebut, $dateFin] = $this->periode($request);

        return view('caisse.print', [
            'caisse' => $caisse,
            'details' => $this->detailsPeriode($caisse, $dateDebut, $dateFin),
            'dateDebut' => $dateDebut,
            'dateFin' => $dateFin,
            'company' => Company::where('is_actif', true)->first(),
        ]);
    }

    /**
     * Période sélectionnée, par défaut la journée en cours
     */
    private function periode(Request $request): array
    {
        $dateDebut = $request->filled('date_debut') ? Carbon::parse($request->date_debut) : today();
        $dateFin = $request->filled('date_fin') ? Carbon::parse($request->date_fin) : $dateDebut->copy();

        if ($dateFin->lt($dateDebut)) {
            [$dateDebut, $dateFin] = [$dateFin, $dateDebut];
        }

        return [$dateDebut->startOfDay(), $dateFin->endOfDay()];
    }

    private function detailsPeriode(Caisse $caisse, Carbon $dateDebut, Carbon $dateFin)
    {
        return $caisse->caisseDetails()
            ->with('user')
            ->whereBetween('created_at', [$dateDebut, $dateFin])
            ->get();
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
