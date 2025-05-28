<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Category;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\StockMovement;
use App\Models\MouvementStock;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class StockController extends Controller
{
    // Display a listing of the stocks
    public function index(Request $request)
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $stocks = Stock::with('category')
            ->when($search, function ($query, $search) {
                $query->where('product_name', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        return view('stock.index', compact('stocks'));
    }

    /**
     * Display the specified stock.
     */
    public function movement($stock)
    {
        $stock = Stock::with('mouvements')->findOrFail($stock);
        $mouvements = $stock->mouvements()->orderBy('created_at', 'desc')->paginate(10);
        $mouvementsTypes = MOUVEMENT_STOCK;
        return view('stock.mouvement', compact('stock', 'mouvements', 'mouvementsTypes'));
    }

    // Show the form for creating a new stock
    public function create()
    {
        $categories = Category::all();
        $suppliers = Supplier::all(); // Retrieve all suppliers for the dropdown
        return view('stock.create', compact('categories', 'suppliers'));
    }

    // Store a newly created stock in storage
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code_product' => 'nullable|string|max:255',
            'product_name' => 'required|string|max:255',
            'marque' => 'nullable|string|max:255',
            'unite_mesure' => 'nullable|string|max:255',
            'quantite' => 'required|numeric|min:0',
            'quantite_alert' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'price_ttc' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'price_tvac' => 'nullable|numeric|min:0',
            'taux_tva' => 'nullable|numeric|min:0',
            'item_ott_tax' => 'nullable|numeric|min:0',
            'item_tsce_tax' => 'nullable|numeric|min:0',
            'price_min' => 'nullable|numeric|min:0',
            'date_expiration' => 'nullable|date',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:100',
            'supplier' => 'nullable|string|max:255',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:Disponible,Faible_stock,En_rupture,Expire',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        Stock::create($validated);

        return redirect()->route('stocks.index')->with('success', 'Stock créé avec succès.');
    }

    // Display the specified stock
    public function show(Stock $stock)
    {
        $category = $stock->category; // Retrieve the associated category
        $user = $stock->user; // Retrieve the associated user
        return view('stock.show', compact('stock', 'category', 'user'));
    }

    // Show the form for editing the specified stock
    public function edit($id)
    {
        $stock = Stock::findOrFail($id);
        $categories = Category::all(); // Retrieve all categories for the dropdown
        $suppliers = Supplier::all(); // Retrieve all suppliers for the dropdown
        return view('stock.edit', compact('stock', 'categories', 'suppliers'));
    }

    // Update the specified stock in storage
    public function update(Request $request, Stock $stock)
    {
        $validated = $request->validate([
            'code_product' => 'nullable|string|max:255',
            'product_name' => 'required|string|max:255',
            'marque' => 'nullable|string|max:255',
            'unite_mesure' => 'nullable|string|max:255',
            'quantite' => 'required|numeric|min:0',
            'quantite_alert' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'price_ttc' => 'nullable|numeric|min:0',
            'price_max' => 'nullable|numeric|min:0',
            'price_tvac' => 'nullable|numeric|min:0',
            'taux_tva' => 'nullable|numeric|min:0',
            'item_ott_tax' => 'nullable|numeric|min:0',
            'item_tsce_tax' => 'nullable|numeric|min:0',
            'price_min' => 'nullable|numeric|min:0',
            'date_expiration' => 'nullable|date',
            'description' => 'nullable|string',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
            'status' => 'required|in:Disponible,Faible_stock,En_rupture,Expire',
            'supplier_id' => 'nullable|exists:suppliers,id',
        ]);

        $stock->update($validated);

        return redirect()->route('stocks.index')->with('success', 'Stock mis à jour avec succès.');
    }

    // Remove the specified stock from storage
    public function destroy(Stock $stock)
    {
        $stock->delete();

        return redirect()->route('stocks.index')->with('success', 'Stock supprimé avec succès.');
    }

    // Liste des produits du stock en alerte
    public function alert(Request $request)
    {
         $search = $request->input('search');
        $status = $request->input('status');
        $stocks = Stock::lowStock()->with('category')
        ->when($search, function ($query, $search) {
                $query->where('product_name', 'like', "%{$search}%");
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->latest()
            ->paginate(10);

        return view('stock.alert', compact('stocks'));
    }


    public function utilisateur()
    {
        // Statistiques générales
        $totalStock = Stock::count();
        $stockFaible = Stock::lowStock()->count();
        $stockExpire = Stock::expirationSoon(30)->count();

        $valeurTotale = Stock::active()->sum(DB::raw('quantite * price'));

        // Mouvements récents (derniers 30 jours)
        $mouvementsRecents = MouvementStock::with('stock')
            ->recent(30)
            ->orderBy('item_movement_date', 'desc')
            ->limit(10)
            ->get();

        // Données pour les graphiques
        $evolutionStock = $this->getEvolutionData();
        $typesMouvements = $this->getMovementTypes();
        $produitsPopulaires = $this->getPopularProducts();
        $stockParCategorie = $this->getCategoryValues();

        // Alertes
        $alertes = [
            'stock_faible' => Stock::lowStock()->with('category')->get(),
            'expiration_proche' => Stock::expirationSoon(30)->with('category')->get(),
            'stock_expire' => Stock::expired()->with('category')->get()
        ];
        // dd($alertes['stock_expire']);

        return view('stock.utilisation', compact(
            'totalStock', 'stockFaible', 'stockExpire', 'valeurTotale',
            'mouvementsRecents', 'evolutionStock', 'typesMouvements',
            'produitsPopulaires', 'stockParCategorie', 'alertes'
        ));
    }

    /**
     * Données d'évolution du stock pour les 6 derniers mois
     */
    public function getEvolutionData(): array
    {
        $evolutionStock = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);

            $entrees = MouvementStock::entrees()
                ->whereMonth('item_movement_date', $date->month)
                ->whereYear('item_movement_date', $date->year)
                ->sum('item_quantity');


            $sorties = MouvementStock::sorties()
                ->whereMonth('item_movement_date', $date->month)
                ->whereYear('item_movement_date', $date->year)
                ->sum('item_quantity');

            $evolutionStock[] = [
                'mois' => $date->format('M Y'),
                'entrees' => (float) $entrees,
                'sorties' => (float) $sorties,
                'net' => (float) ($entrees - $sorties)
            ];
        }

        return $evolutionStock;
    }

    /**
     * Répartition par type de mouvement (30 derniers jours)
     */
    public function getMovementTypes(): array
    {
        return MouvementStock::select('item_movement_type', DB::raw('COUNT(*) as count'))
            ->recent(30)
            ->groupBy('item_movement_type')
            ->get()
            ->map(function($item) {
                return [
                    'type' => MouvementStock::MOUVEMENT_TYPES[$item->item_movement_type] ?? $item->item_movement_type,
                    'count' => $item->count,
                    'color' => str_starts_with($item->item_movement_type, 'E') ? '#10B981' : '#EF4444'
                ];
            })
            ->toArray();
    }

    /**
     * Top 10 des produits les plus utilisés (30 derniers jours)
     */
    public function getPopularProducts(): array
    {
        return MouvementStock::select('item_designation', DB::raw('SUM(ABS(item_quantity)) as total_quantity'))
            ->recent(30)
            ->groupBy('item_designation')
            ->orderBy('total_quantity', 'desc')
            ->limit(10)
            ->get()
            ->toArray();
    }

    /**
     * Analyse de la valeur du stock par catégorie
     */
    public function getCategoryValues(): array
    {
        return Stock::join('categories', 'stocks.category_id', '=', 'categories.id')
            ->select('categories.name as categorie',
                    DB::raw('SUM(stocks.quantite * stocks.price) as valeur'),
                    DB::raw('COUNT(stocks.id) as nombre_produits'),
                    DB::raw('SUM(stocks.quantite) as quantite_totale'))
            ->where('stocks.status', 'Disponible')
            ->groupBy('categories.name')
            ->orderBy('valeur', 'desc')
            ->get()
            ->toArray();
    }

    /**
     * API Routes pour AJAX
     */
    public function getEvolutionDataApi(): JsonResponse
    {
        return response()->json($this->getEvolutionData());
    }

    public function getMovementTypesApi(): JsonResponse
    {
        return response()->json($this->getMovementTypes());
    }

    public function getPopularProductsApi(): JsonResponse
    {
        return response()->json($this->getPopularProducts());
    }

    public function getCategoryValuesApi(): JsonResponse
    {
        return response()->json($this->getCategoryValues());
    }

    /**
     * Rapports détaillés
     */
    public function analytics()
    {
        $analytics = [
            'stock_overview' => [
                'total_products' => Stock::active()->count(),
                'total_value' => Stock::active()->sum(DB::raw('quantite * price')),
                'average_value_per_product' => Stock::active()->avg(DB::raw('quantite * price')),
                'total_quantity' => Stock::active()->sum('quantite'),
            ],
            'movement_analytics' => [
                'total_movements_this_month' => MouvementStock::whereMonth('item_movement_date', now()->month)->count(),
                'entries_this_month' => MouvementStock::entrees()->whereMonth('item_movement_date', now()->month)->sum('item_quantity'),
                'exits_this_month' => MouvementStock::sorties()->whereMonth('item_movement_date', now()->month)->sum('item_quantity'),
                'net_movement_this_month' => MouvementStock::entrees()->whereMonth('item_movement_date', now()->month)->sum('item_quantity') -
                                           MouvementStock::sorties()->whereMonth('item_movement_date', now()->month)->sum('item_quantity'),
            ],
            'alerts_summary' => [
                'low_stock_count' => Stock::lowStock()->count(),
                'expiring_soon_count' => Stock::expirationSoon(30)->count(),
                'expired_count' => Stock::expired()->count(),
                'zero_stock_count' => Stock::where('quantite', 0)->count(),
            ]
        ];

        return view('stock.analytics', compact('analytics'));
    }

    public function movementReport(Request $request)
    {
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $type = $request->get('type', 'all');

        $query = MouvementStock::with(['stock'])
            ->whereBetween('item_movement_date', [$startDate, $endDate]);

        if ($type !== 'all') {
            if ($type === 'entries') {
                $query->entrees();
            } elseif ($type === 'exits') {
                $query->sorties();
            }
        }

        $movements = $query->orderBy('item_movement_date', 'desc')->paginate(50);

        $summary = [
            'total_movements' => $movements->total(),
            'total_entries' => MouvementStock::entrees()->whereBetween('item_movement_date', [$startDate, $endDate])->sum('item_quantity'),
            'total_exits' => MouvementStock::sorties()->whereBetween('item_movement_date', [$startDate, $endDate])->sum('item_quantity'),
            'net_movement' => MouvementStock::entrees()->whereBetween('item_movement_date', [$startDate, $endDate])->sum('item_quantity') -
                            MouvementStock::sorties()->whereBetween('item_movement_date', [$startDate, $endDate])->sum('item_quantity'),
        ];

        return view('stock.reports.movements', compact('movements', 'summary', 'startDate', 'endDate', 'type'));
    }

    public function alertsReport()
    {
        $alerts = [
            'low_stock' => Stock::lowStock()->with(['category'])->orderBy('quantite', 'asc')->get(),
            'expiring_soon' => Stock::expirationSoon(30)->with(['category'])->orderBy('date_expiration', 'asc')->get(),
            'expired' => Stock::expired()->with(['category'])->orderBy('date_expiration', 'desc')->get(),
            'zero_stock' => Stock::where('quantite', 0)->with(['category'])->get(),
        ];

        $summary = [
            'total_alerts' => collect($alerts)->sum(fn($items) => $items->count()),
            'critical_alerts' => $alerts['zero_stock']->count() + $alerts['expired']->count(),
            'warning_alerts' => $alerts['low_stock']->count() + $alerts['expiring_soon']->count(),
        ];

        return view('stock.reports.alerts', compact('alerts', 'summary'));
    }

    public function valuationReport()
    {
        $valuation = [
            'by_category' => $this->getCategoryValues(),
            'by_supplier' => Stock::join('suppliers', 'stocks.supplier_id', '=', 'suppliers.id')
                ->select('suppliers.name as supplier',
                        DB::raw('SUM(stocks.quantite * stocks.price) as valeur'),
                        DB::raw('COUNT(stocks.id) as nombre_produits'))
                ->where('stocks.status', 'Disponible')
                ->groupBy('suppliers.name')
                ->orderBy('valeur', 'desc')
                ->get(),
            'total_value' => Stock::active()->sum(DB::raw('quantite * price')),
            'average_product_value' => Stock::active()->avg(DB::raw('quantite * price')),
            'top_value_products' => Stock::active()
                ->orderBy(DB::raw('quantite * price'), 'desc')
                ->limit(10)
                ->get(),
        ];

        return view('stock.reports.valuation', compact('valuation'));
    }

    /**
     * Alertes spécifiques
     */
    public function lowStockAlert()
    {
        $lowStockItems = Stock::lowStock()->with(['category'])->orderBy('quantite', 'asc')->paginate(20);
        return view('stock.alerts.low-stock', compact('lowStockItems'));
    }

    public function expiringAlert()
    {
        $expiringItems = Stock::expirationSoon(30)->with(['category'])->orderBy('date_expiration', 'asc')->paginate(20);
        return view('stock.alerts.expiring', compact('expiringItems'));
    }

    public function expiredAlert()
    {
        $expiredItems = Stock::expired()->with(['category'])->orderBy('date_expiration', 'desc')->paginate(20);
        return view('stock.alerts.expired', compact('expiredItems'));
    }
}
