<?php

/**
 * Web Routes
 *
 * Here is where you can register web routes for your application.
 *
 * @category Routes
 * @package  App\Routes
 * @author   Your Name <your.email@example.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/your-username/cabinet-dentaire
 */

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\DentistController;
use App\Http\Controllers\AppointmentController;
use App\Http\Controllers\TreatmentController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TreatmentTypeController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\AssuranceController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\MouvementStockController;
use App\Http\Controllers\MovementStockController;
use App\Http\Controllers\StockMovementController;
use App\Http\Controllers\ParametrageController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect()->route('login');
});
Route::get(
    'appointments/events',
    [AppointmentController::class, 'events']
)->name('appointments.events');

//patients.search
Route::get('patients/search',[PatientController::class, 'search'])->name('patients.search');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');
//->can('is-admin');

Route::middleware(['auth', 'canany:is-admin,is-pharmacist'])->group(function () {
    Route::resource('invoices', InvoiceController::class);
    Route::resource('orders', OrderController::class);
});

Route::middleware(['auth'])->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::patch('/profile/address', [ProfileController::class, 'updateAddress'])->name('profile.update.address');
    Route::patch('/profile/photo', [ProfileController::class, 'updatePhoto'])->name('profile.update.photo');
    Route::put('/password', [PasswordController::class, 'update'])->name('password.update');
    Route::get('parametres', [ParametrageController::class, 'index'])->name('parametres');

    // Route::get('/parametrage', [ParametrageController::class, 'index'])->name('parametrage.index');

    // Routes pour les informations de l'entreprise
    Route::put('/parametrage/company/update', [ParametrageController::class, 'updateCompany'])->name('parametrage.company.update');
    Route::resource('patients', PatientController::class);
    Route::resource('dentists', DentistController::class);
    Route::resource('treatments', TreatmentController::class);
    Route::resource('mouvements_stocks', MouvementStockController::class);
    // Can is Admin
    Route::resource('payments', PaymentController::class);
    Route::resource('stocks', StockController::class);
    Route::resource('users', UserController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('suppliers', SupplierController::class);

    Route::resource('assurances', AssuranceController::class);
    Route::get('invoices/{id}/pdf', [InvoiceController::class, 'generatePdf'])->name('invoices.pdf');
    Route::get('stock/alert', [StockController::class, 'alert'])->name('invoice.alert');
    Route::get("invoices_obr", [InvoiceController::class, 'invoices_obr' ])->name('invoices_obr');
    Route::get("invoices.send-to-obr/{id}", [InvoiceController::class, 'sendToObr' ])->name('invoices.send-to-obr');
    Route::get("invoices.cancel-to-obr/{id}", [InvoiceController::class, 'cancelToObr' ])->name('invoices.cancel-to-obr');
    // Routes pour les rendez-vous
    Route::get('/appointments/today', [
        AppointmentController::class,
        'today'
    ])->name('appointments.today');

    Route::get('/appointments/calendar', [AppointmentController::class, 'calendar'])->name('appointments.calendar');

    Route::resource('appointments', AppointmentController::class);
    Route::get('stocks/{id}/movement', [StockController::class, 'movement'])->name('stocks.movement');
    // Dashboard related routes
    Route::get(
        'dashboard/revenue',
        [DashboardController::class, 'revenue']
    )->name('dashboard.revenue');

    Route::get(
        'dashboard/today-appointments',
        [DashboardController::class, 'todayAppointments']
    )->name('dashboard.today-appointments');

    Route::get(
        'dashboard/new-patients',
        [DashboardController::class, 'newPatients']
    )->name('dashboard.new-patients');

    Route::get(
        'dashboard/unpaid-invoices',
        [DashboardController::class, 'unpaidInvoices']
    )->name('dashboard.unpaid-invoices');

    Route::get(
        'dashboard/monthly-invoices',
        [DashboardController::class, 'monthlyInvoices']
    )->name('dashboard.monthly-invoices');

    Route::get(
        'rendezvous/today',
        [AppointmentController::class, 'today']
    )->name('appointments.today');

    Route::get(
        'rendezvous/finish/{appointment}',
        [AppointmentController::class, 'finish']
    )->name('appointments.finish');
    Route::get(
        'rendezvous/cancel/{appointment}',
        [AppointmentController::class, 'cancel']
    )->name('appointments.cancel');
    Route::get('rendezvous/reschedule/{appointment}',[AppointmentController::class, 'reschedule'])->name('appointments.reschedule');
    Route::get('rendezvous/confirm/{appointment}',[AppointmentController::class, 'confirm'])->name('appointments.confirm');

    Route::get(
        'patients/new',
        [PatientController::class, 'new']
    )->name('patients.new');

    //factures.monthly
    Route::get(
        'factures.monthly',
        [InvoiceController::class, 'monthly']
    )->name('factures.monthly');

    Route::get(
        'factures.unpaid',
        [InvoiceController::class, 'unpaid']
    )->name('factures.unpaid');

    Route::get(
        'factures.new',
        [InvoiceController::class, 'new']
    )->name('factures.new');

    //rendezvous.calendar
    Route::get(
        'rendezvous/calendar',
        [AppointmentController::class, 'calendar']
    )->name('rendezvous.calendar');

    //rendezvous.create
    Route::get(
        'rendezvous/create',
        [AppointmentController::class, 'create']
    )->name('rendezvous.create');

    //rendezvous.edit
    Route::get(
        'rendezvous/edit/{id}',
        [AppointmentController::class, 'edit']
    )->name('rendezvous.edit');

    //rendezvous.update
    Route::put(
        'rendezvous/update/{id}',
        [AppointmentController::class, 'update']
    )->name('rendezvous.update');

    //rendezvous.delete
    Route::delete(
        'rendezvous/delete/{id}',
        [AppointmentController::class, 'destroy']
    )->name('rendezvous.delete');

    //rendezvous.today
    Route::get(
        'rendezvous/today',
        [AppointmentController::class, 'today']
    )->name('rendezvous.today');

    //appointments.today
    Route::get(
        'appointments/today',
        [AppointmentController::class, 'today']
    )->name('appointments.today');
    //events

    //settings
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index'])->name('settings');

        // Treatment Types
        Route::resource('treatment-types', TreatmentTypeController::class)->names([
            'index' => 'settings.treatment-types.index',
            'create' => 'settings.treatment-types.create',
            'store' => 'settings.treatment-types.store',
            'show' => 'settings.treatment-types.show',
            'edit' => 'settings.treatment-types.edit',
            'update' => 'settings.treatment-types.update',
            'destroy' => 'settings.treatment-types.destroy',
        ]);

        // Payment Methods
        Route::resource('payment-methods', PaymentMethodController::class)->names([
            'index' => 'settings.payment-methods.index',
            'create' => 'settings.payment-methods.create',
            'store' => 'settings.payment-methods.store',
            'show' => 'settings.payment-methods.show',
            'edit' => 'settings.payment-methods.edit',
            'update' => 'settings.payment-methods.update',
            'destroy' => 'settings.payment-methods.destroy',
        ]);
    });

    Route::resource('companies', CompanyController::class);
    Route::resource('caisses', App\Http\Controllers\CaisseController::class);
    Route::patch('/{caisse}/withdraw', [App\Http\Controllers\CaisseController::class, 'withdraw'])->name('caisses.withdraw');

    Route::resource('caisse-details', App\Http\Controllers\CaisseDetailController::class);

});

Route::resource('stock_movements', StockMovementController::class);

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::prefix('admin/sessions')->name('admin.sessions.')->group(function () {
        Route::post('/logout/{userId}', [AdminController::class, 'logoutUser'])->name('logout.user');
        Route::post('/logout-all', [AdminController::class, 'logoutAllUsers'])->name('logout.all');
        Route::post('/suspend/{sessionId}', [AdminController::class, 'suspendSession'])->name('suspend');
        Route::post('/suspend-inactive', [AdminController::class, 'suspendInactiveSessions'])->name('suspend.inactive');
        Route::get('/export', [AdminController::class, 'exportSessions'])->name('export');
    });
    Route::get('/admin/stats/update', [AdminController::class, 'updateStats'])->name('admin.stats.update');
    Route::get('/admin/settings', [AdminController::class, 'settings'])->name('admin.settings.index');
});

Route::prefix('stock')->name('stock.')->group(function () {
    // Tableau de bord utilisateur
    Route::get('/utilisateur', [StockController::class, 'utilisateur'])->name('utilisateur');
    // Route pour le rapport
    Route::get('/stocks-rapport', [StockController::class, 'rapport'])->name('rapport');
    Route::get('/stocks-syncronisation', [StockController::class, 'syncronisation'])->name('syncronisation');

    // Routes d'export
    Route::get('/stocks/export/excel', [StockController::class, 'exportExcel'])->name('export-excel');
    Route::get('/stocks/export/pdf', [StockController::class, 'exportPdf'])->name('export-pdf');

    // Routes d'import
    Route::get('/stocks/import/form', [StockController::class, 'importForm'])->name('import-form');
    Route::post('/stocks/import', [StockController::class, 'import'])->name('import');
    Route::get('/stocks/template/download', [StockController::class, 'downloadTemplate'])->name('download-template');
    // Route::get('/report', [StockController::class, 'report'])->name('report');

    // Routes pour les rapports et statistiques
    Route::get('/reports/analytics', [StockController::class, 'analytics'])->name('reports.analytics');
    Route::get('/reports/movements', [StockController::class, 'movementReport'])->name('reports.movements');
    Route::get('/reports/alerts', [StockController::class, 'alertsReport'])->name('reports.alerts');
    Route::get('/reports/valuation', [StockController::class, 'valuationReport'])->name('reports.valuation');

    // Routes API pour les données des graphiques
    Route::get('/api/evolution-data', [StockController::class, 'getEvolutionData'])->name('api.evolution');
    Route::get('/api/movement-types', [StockController::class, 'getMovementTypes'])->name('api.movement-types');
    Route::get('/api/popular-products', [StockController::class, 'getPopularProducts'])->name('api.popular-products');
    Route::get('/api/category-values', [StockController::class, 'getCategoryValues'])->name('api.category-values');

    // Routes pour les alertes
    Route::get('/alerts/low-stock', [StockController::class, 'lowStockAlert'])->name('alerts.low-stock');
    Route::get('/alerts/expiring', [StockController::class, 'expiringAlert'])->name('alerts.expiring');
    Route::get('/alerts/expired', [StockController::class, 'expiredAlert'])->name('alerts.expired');

    // Export routes
    Route::get('/export/excel', [StockController::class, 'exportExcel'])->name('export.excel');
    Route::get('/export/pdf', [StockController::class, 'exportPdf'])->name('export.pdf');
});

require __DIR__.'/auth.php';


Route::resource('obr-request-bodies', App\Http\Controllers\ObrRequestBodyController::class);

Route::resource('obr-pointers', App\Http\Controllers\ObrPointerController::class);


Route::resource('obr-request-bodies', App\Http\Controllers\ObrRequestBodyController::class);

Route::resource('obr-pointers', App\Http\Controllers\ObrPointerController::class);
