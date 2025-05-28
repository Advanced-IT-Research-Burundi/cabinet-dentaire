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
        $caissePrincipale = Caisse::find(1)->first()->montant; // Assuming the main caisse has ID 1

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

    public function withdraw(Request $request, Caisse $caisse){
        // Validation des données
        $validated = $request->validate([
            'montant_retrait' => ['required','numeric','min:0.01','max:' . $caisse->montant],
            'motif_retrait' => ['required','string','min:5','max:255']
        ],
        [
            'montant_retrait.required' => 'Le montant à retirer est obligatoire.',
            'montant_retrait.numeric' => 'Le montant doit être un nombre.',
            'montant_retrait.min' => 'Le montant doit être supérieur à 0.',
            'montant_retrait.max' => 'Le montant ne peut pas dépasser le solde disponible.',
            'motif_retrait.required' => 'Le motif du retrait est obligatoire.',
            'motif_retrait.min' => 'Le motif doit contenir au moins 5 caractères.',
            'motif_retrait.max' => 'Le motif ne peut pas dépasser 255 caractères.'
        ]);

        try {
            \DB::beginTransaction();

            // Vérification de sécurité supplémentaire
            if ($validated['montant_retrait'] > $caisse->montant) {
                return back()->withErrors([
                    'montant_retrait' => 'Solde insuffisant dans la caisse.'
                ])->withInput();
            }

            $nouveauMontant = $caisse->montant - $validated['montant_retrait'];

            $caisse->update([
                'montant' => $nouveauMontant
            ]);
            // Ajout du montant retraint dans la caisse principale
            Caisse::where('id', 1)->increment('montant', $validated['montant_retrait']);
            // Enregistrement du retrait dans les détails de la caisse principale
            CaisseDetail::create([
                'caisse_id' => 1,
                'type' => "MONTANT RETRAIT",
                'price' => $validated['montant_retrait'],
                'total' => $validated['montant_retrait'],
                'status' => '1',
                'user_id' => auth()->user()->id,
                'description' => $validated['motif_retrait'],
            ]);

            // Enregistrement du retrait dans les détails de la caisse spécifique
            CaisseDetail::create([
                'caisse_id' => $caisse->id,
                'type' => "MONTANT RETRAIT",
                'price' => 0,
                'total' => -($validated['montant_retrait']),
                'status' => '1',
                'user_id' => auth()->user()->id,
                'description' => $validated['motif_retrait'],
            ]);


            // Message de succès
            $message = sprintf(
                'Retrait de %s FBU effectué avec succès. Nouveau solde : %s FBU',
                number_format($validated['montant_retrait'], 0, ',', ' '),
                number_format($nouveauMontant, 0, ',', ' ')
            );
            \DB::commit();
            return redirect()->route('caisses.index')
                ->with('success', $message);


        } catch (\Exception $e) {
            // Gestion des erreurs
            \Log::error('Erreur lors du retrait de la caisse', [
                'caisse_id' => $caisse->id,
                'montant_retrait' => $validated['montant_retrait'],
                'error' => $e->getMessage()
            ]);

            return back()->withErrors([
                'error' => 'Une erreur est survenue lors du retrait. Veuillez réessayer.'
            ])->withInput();
        }


    }

}
