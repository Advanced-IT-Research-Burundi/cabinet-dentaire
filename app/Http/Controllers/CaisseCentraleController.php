<?php

namespace App\Http\Controllers;

use App\Models\Caisse;
use App\Models\CaisseDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class CaisseCentraleController extends Controller
{
    private const JUSTIFICATIF_DIR = 'justificatifs/caisse-centrale';
    private const JUSTIFICATIF_RULES = ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'];

    public function index(Request $request)
    {
        $centrale = Caisse::centrale();

        $query = CaisseDetail::with(['user', 'sourceCaisse.user'])
            ->where('caisse_id', $centrale->id);

        if ($request->filled('operation_type')) {
            $query->where('operation_type', $request->operation_type);
        }
        if ($request->filled('sens')) {
            $query->where('sens', $request->sens);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $operations = $query->latest()->paginate(15)->withQueryString();

        $debutMois = now()->startOfMonth();
        $stats = [
            'entrees_mois' => CaisseDetail::where('caisse_id', $centrale->id)
                ->where('created_at', '>=', $debutMois)->where('total', '>', 0)->sum('total'),
            'sorties_mois' => abs(CaisseDetail::where('caisse_id', $centrale->id)
                ->where('created_at', '>=', $debutMois)->where('total', '<', 0)->sum('total')),
            'collectes_mois' => CaisseDetail::where('caisse_id', $centrale->id)
                ->where('created_at', '>=', $debutMois)->where('operation_type', COLLECTE_CAISSE)->sum('total'),
        ];

        $caissesUtilisateurs = Caisse::utilisateurs()->with('user')
            ->where('montant', '>', 0)
            ->orderByDesc('montant')
            ->get();

        return view('caisse.centrale', [
            'centrale' => $centrale,
            'operations' => $operations,
            'stats' => $stats,
            'caissesUtilisateurs' => $caissesUtilisateurs,
        ]);
    }

    /**
     * Enregistre une opération bancaire sur la caisse centrale.
     */
    public function storeOperation(Request $request)
    {
        $validated = $request->validate([
            'operation_type' => ['required', Rule::in(array_keys(OPERATIONS_CAISSE_CENTRALE))],
            'montant' => ['required', 'numeric', 'min:0.01'],
            'date_operation' => ['required', 'date'],
            'banque' => ['nullable', 'string', 'max:255'],
            'reference' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'justificatif' => self::JUSTIFICATIF_RULES,
        ], [
            'operation_type.required' => "Le type d'opération est obligatoire.",
            'montant.min' => 'Le montant doit être supérieur à 0.',
            'justificatif.mimes' => 'Le justificatif doit être un PDF ou une image (jpg, png).',
            'justificatif.max' => 'Le justificatif ne doit pas dépasser 5 Mo.',
        ]);

        $sens = OPERATIONS_CAISSE_CENTRALE[$validated['operation_type']]['sens'];
        $montant = (float) $validated['montant'];
        $path = $request->hasFile('justificatif')
            ? $request->file('justificatif')->store(self::JUSTIFICATIF_DIR)
            : null;

        try {
            DB::transaction(function () use ($validated, $sens, $montant, $path) {
                $centrale = Caisse::whereKey(Caisse::centrale()->id)->lockForUpdate()->first();

                if ($sens === 'sortie' && $montant > $centrale->montant) {
                    throw new \DomainException('Solde insuffisant dans la caisse centrale.');
                }

                $total = $sens === 'entree' ? $montant : -$montant;
                $centrale->increment('montant', $total);

                CaisseDetail::create([
                    'caisse_id' => $centrale->id,
                    'type' => OPERATIONS_CAISSE_CENTRALE[$validated['operation_type']]['label'],
                    'operation_type' => $validated['operation_type'],
                    'sens' => $sens,
                    'date_operation' => $validated['date_operation'],
                    'banque' => $validated['banque'] ?? null,
                    'reference' => $validated['reference'] ?? null,
                    'justificatif' => $path,
                    'price' => $montant,
                    'total' => $total,
                    'status' => '1',
                    'user_id' => auth()->id(),
                    'description' => $validated['description'] ?? null,
                ]);
            });
        } catch (\DomainException $e) {
            $this->deleteFile($path);
            return back()->withErrors(['montant' => $e->getMessage()])->withInput();
        } catch (\Throwable $e) {
            $this->deleteFile($path);
            Log::error("Erreur lors de l'opération sur la caisse centrale", ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Une erreur est survenue. Veuillez réessayer.'])->withInput();
        }

        return redirect()->route('caisse-centrale.index')
            ->with('success', 'Opération enregistrée avec succès.');
    }

    /**
     * Diminue le montant d'une caisse utilisateur et le transfère vers la caisse centrale.
     */
    public function collecter(Request $request, Caisse $caisse)
    {
        abort_if($caisse->is_centrale, 422, 'Impossible de collecter la caisse centrale.');

        $validated = $request->validate([
            'montant_retrait' => ['required', 'numeric', 'min:0.01', 'max:' . $caisse->montant],
            'motif_retrait' => ['required', 'string', 'min:5', 'max:255'],
            'justificatif' => self::JUSTIFICATIF_RULES,
        ], [
            'montant_retrait.required' => 'Le montant à retirer est obligatoire.',
            'montant_retrait.numeric' => 'Le montant doit être un nombre.',
            'montant_retrait.min' => 'Le montant doit être supérieur à 0.',
            'montant_retrait.max' => 'Le montant ne peut pas dépasser le solde disponible.',
            'motif_retrait.required' => 'Le motif du retrait est obligatoire.',
            'motif_retrait.min' => 'Le motif doit contenir au moins 5 caractères.',
            'motif_retrait.max' => 'Le motif ne peut pas dépasser 255 caractères.',
            'justificatif.mimes' => 'Le justificatif doit être un PDF ou une image (jpg, png).',
            'justificatif.max' => 'Le justificatif ne doit pas dépasser 5 Mo.',
        ]);

        $montant = (float) $validated['montant_retrait'];
        $path = $request->hasFile('justificatif')
            ? $request->file('justificatif')->store(self::JUSTIFICATIF_DIR)
            : null;

        try {
            $nouveauSolde = DB::transaction(function () use ($caisse, $validated, $montant, $path) {
                $centraleId = Caisse::centrale()->id;
                $source = Caisse::whereKey($caisse->id)->lockForUpdate()->first();
                $centrale = Caisse::whereKey($centraleId)->lockForUpdate()->first();

                if ($montant > $source->montant) {
                    throw new \DomainException('Solde insuffisant dans la caisse.');
                }

                $source->decrement('montant', $montant);
                $centrale->increment('montant', $montant);

                $commun = [
                    'type' => 'MONTANT RETRAIT',
                    'operation_type' => COLLECTE_CAISSE,
                    'date_operation' => now()->toDateString(),
                    'justificatif' => $path,
                    'price' => $montant,
                    'status' => '1',
                    'user_id' => auth()->id(),
                    'description' => $validated['motif_retrait'],
                    'source_caisse_id' => $source->id,
                ];

                CaisseDetail::create($commun + [
                    'caisse_id' => $centrale->id,
                    'sens' => 'entree',
                    'total' => $montant,
                ]);

                CaisseDetail::create($commun + [
                    'caisse_id' => $source->id,
                    'sens' => 'sortie',
                    'total' => -$montant,
                ]);

                return $source->montant;
            });
        } catch (\DomainException $e) {
            $this->deleteFile($path);
            return back()->withErrors(['montant_retrait' => $e->getMessage()])->withInput();
        } catch (\Throwable $e) {
            $this->deleteFile($path);
            Log::error('Erreur lors de la collecte de la caisse', [
                'caisse_id' => $caisse->id,
                'montant_retrait' => $montant,
                'error' => $e->getMessage(),
            ]);
            return back()->withErrors(['error' => 'Une erreur est survenue lors du retrait. Veuillez réessayer.'])->withInput();
        }

        return back()->with('success', sprintf(
            'Retrait de %s FBU transféré vers la caisse centrale. Nouveau solde : %s FBU',
            number_format($montant, 0, ',', ' '),
            number_format($nouveauSolde, 0, ',', ' ')
        ));
    }

    /**
     * Ajoute (ou remplace) la pièce justificative d'une opération existante.
     */
    public function attachJustificatif(Request $request, CaisseDetail $detail)
    {
        $request->validate([
            'justificatif' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ], [
            'justificatif.required' => 'Veuillez choisir un fichier.',
            'justificatif.mimes' => 'Le justificatif doit être un PDF ou une image (jpg, png).',
            'justificatif.max' => 'Le justificatif ne doit pas dépasser 5 Mo.',
        ]);

        $ancien = $detail->justificatif;
        $detail->update(['justificatif' => $request->file('justificatif')->store(self::JUSTIFICATIF_DIR)]);

        // Un justificatif de collecte est partagé entre les deux lignes (caisse utilisateur / centrale)
        if ($detail->operation_type === COLLECTE_CAISSE && $detail->source_caisse_id) {
            CaisseDetail::where('source_caisse_id', $detail->source_caisse_id)
                ->where('operation_type', COLLECTE_CAISSE)
                ->where('created_at', $detail->created_at)
                ->whereKeyNot($detail->id)
                ->update(['justificatif' => $detail->justificatif]);
        }

        if ($ancien && !CaisseDetail::where('justificatif', $ancien)->exists()) {
            $this->deleteFile($ancien);
        }

        return back()->with('success', 'Pièce justificative ajoutée.');
    }

    public function justificatif(CaisseDetail $detail)
    {
        abort_unless($detail->justificatif && Storage::exists($detail->justificatif), 404);

        return Storage::response($detail->justificatif);
    }

    private function deleteFile(?string $path): void
    {
        if ($path) {
            Storage::delete($path);
        }
    }
}
