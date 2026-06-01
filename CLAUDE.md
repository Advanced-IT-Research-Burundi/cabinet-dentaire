# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Budental Services is a dental practice management system built with Laravel 10 and modern frontend technologies. It handles patient management, appointments, treatments, invoicing with OBR fiscal API integration, and stock/inventory management.

## Development Commands

```bash
# Install dependencies
composer install
npm install

# Development servers
php artisan serve          # Laravel server (http://localhost:8000)
npm run dev               # Vite dev server for assets

# Database
php artisan migrate       # Run migrations
php artisan migrate:fresh # Reset and re-run migrations
php artisan tinker        # Interactive shell

# Testing
./vendor/bin/phpunit                                          # Run all tests
./vendor/bin/phpunit tests/Feature/ExampleTest.php            # Run single test file
./vendor/bin/phpunit --filter testMethodName                  # Run specific test method
./vendor/bin/phpunit --testsuite Feature                      # Run feature tests only
./vendor/bin/phpunit --testsuite Unit                         # Run unit tests only

# Code style
./vendor/bin/pint                                             # Laravel Pint (code formatting)

# Production build
npm run build
php artisan optimize
```

## Tech Stack

- **Backend**: Laravel 10, PHP 8.1+, Livewire 3.6
- **Frontend**: AlpineJS, Tailwind CSS, Bootstrap 5, Vite, SweetAlert2
- **Database**: MySQL (budental_services)
- **Auth**: Laravel Breeze with Sanctum
- **Exports**: Maatwebsite Excel, DOMPDF/TCPDF for PDF generation

## Architecture

### Key Directories

- `app/Http/Controllers/` - 30+ resource controllers (RESTful CRUD)
- `app/Models/` - Eloquent models with soft deletes
- `app/Livewire/` - Dynamic components (CreateInvoice, Order)
- `app/Exports/` - Excel export classes (StockExport, MonthlyReportExport, MouvementStockExport)
- `app/Helpers/` - Configuration constants and helper functions (autoloaded via composer)
- `resources/views/` - Blade templates organized by resource
- `routes/web.php` - Main application routes

### Core Models

| Model | Purpose |
|-------|---------|
| User | Auth with roles (Admin, Dentiste, Secretaire, Pharmacist) |
| Patient | Patient records (physique/morale entities) |
| Appointment | Scheduling with dentist assignment and status tracking |
| Treatment | Treatment records linked to patients |
| Invoice | Invoicing with OBR integration, stores company/client as JSON |
| Stock | Product inventory with alerts and expiration tracking |
| MouvementStock | Stock transaction history (uses MOUVEMENT_STOCK constants) |
| Caisse/CaisseDetail | Cash register management |

### Authorization

Role-based access uses custom middleware + Gates defined in `AuthServiceProvider`:

**Middleware** (`app/Http/Middleware/`):
- `admin` (AdminMiddleware) - Admin-only routes, returns 403
- `canany:is-admin,is-pharmacist` (CanAny) - Allows if user has any of the listed abilities

**Gates** (defined in AuthServiceProvider):
- `is-admin`, `is-dentiste`, `is-secretaire`, `is-pharmacist`

**User model methods**: `isAdmin()`, `isDentiste()`, `isSecretaire()`, `isPharmacist()`

### OBR Integration

The system integrates with OBR (Office Burundais des Recettes) for fiscal invoicing:
- Models: `ObrPointer`, `ObrRequestBody`
- Invoice model has `getObrOrderFormatAttribute()` accessor for API formatting
- Routes: `invoices.send-to-obr/{id}`, `invoices.cancel-to-obr/{id}`
- Config via env: `OBR_USERNAME`, `OBR_PASSWORD`, `OBR_URL`

### Key Controllers

- `AdminController` - Dashboard stats, session management, revenue calculations
- `StockController` - Inventory CRUD, reports, import/export, alerts, analytics API endpoints
- `AppointmentController` - Calendar, scheduling, status management (confirm/cancel/finish/reschedule)
- `InvoiceController` - Invoice CRUD with OBR sync and PDF generation
- `ReportController` - Monthly reports with Excel/PDF export

### Stock Movement Types

Constants in `app/Helpers/configuration.php`:
- Entry types: EN (Normal), ER (Return), EI (Inventory), EAJ (Adjustment), ET (Transfer), EAU (Other)
- Exit types: SN (Normal), SP (Loss), SV (Theft), SD (Obsolete), SC (Breakage), SAJ (Adjustment), ST (Transfer), SAU (Other)

## Conventions

- **Language**: Mixed French/English (factures=invoices, rendezvous=appointments, caisse=cash register)
- **Status enums**: Confirme, Annule, Termine, En_attente, Reporte
- **User roles**: Admin, Dentiste, Secretaire, Pharmacist (defined in ROLE_USERS constant)
- **Currency**: Decimal fields with 2 precision (total_amount, insurance_amount)
- **Soft deletes**: Used on User, Patient, Invoice, Stock models
- **JSON columns**: Invoice stores `company`, `client`, `description` as JSON

## Route Structure

Main resource routes follow Laravel conventions:
- `/patients` - Patient management
- `/appointments`, `/rendezvous` - Calendar and scheduling (dual naming)
- `/invoices` - Invoice CRUD with OBR sync
- `/stocks` - Inventory management
- `/stock/*` - Stock reports, analytics, alerts, import/export (prefixed routes)
- `/settings` - Treatment types, payment methods
- `/reports` - Monthly reports with Excel/PDF export
- `/admin` - Admin dashboard and session control
- `/caisses` - Cash register management
