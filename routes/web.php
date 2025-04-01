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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::get(
        '/profile',
        [ProfileController::class, 'edit']
    )->name('profile.edit');

    Route::patch(
        '/profile',
        [ProfileController::class, 'update']
    )->name('profile.update');

    Route::delete(
        '/profile',
        [ProfileController::class, 'destroy']
    )->name('profile.destroy');

    Route::resource('patients', PatientController::class);
    Route::resource('dentists', DentistController::class);
    Route::resource('treatments', TreatmentController::class);
    Route::resource('invoices', InvoiceController::class);
    Route::resource('payments', PaymentController::class);
    Route::resource('stocks', StockController::class);
    Route::resource('users', UserController::class);

    // Routes pour les rendez-vous
    Route::get('/appointments/today', [
        AppointmentController::class,
        'today'
    ])->name('appointments.today');

    Route::resource('appointments', AppointmentController::class);

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

    //patients.search
    Route::get(
        'patients/search',
        [PatientController::class, 'search']
    )->name('patients.search');

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

    //endezvous.today
    Route::get(
        'rendezvous/today',
        [AppointmentController::class, 'today']
    )->name('rendezvous.today');

    //appointments.today
    Route::get(
        'appointments/today',
        [AppointmentController::class, 'today']
    )->name('appointments.today');

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
});

require __DIR__.'/auth.php';
