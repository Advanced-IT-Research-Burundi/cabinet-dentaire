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
./vendor/bin/phpunit                           # Run all tests
./vendor/bin/phpunit tests/Feature/ExampleTest.php  # Run single test file

# Production build
npm run build
php artisan optimize
```

## Tech Stack

- **Backend**: Laravel 10, PHP 8.1+, Livewire 3.6
- **Frontend**: AlpineJS, Tailwind CSS, Bootstrap 5, Vite
- **Database**: MySQL (budental_services)
- **Auth**: Laravel Breeze with Sanctum
- **Exports**: Maatwebsite Excel, DOMPDF for PDF generation

## Architecture

### Key Directories

- `app/Http/Controllers/` - 30+ resource controllers (RESTful CRUD)
- `app/Models/` - Eloquent models with soft deletes
- `app/Livewire/` - Dynamic components (e.g., Order)
- `app/Exports/` - Excel export classes
- `resources/views/` - Blade templates organized by resource
- `routes/web.php` - Main application routes

### Core Models

| Model | Purpose |
|-------|---------|
| User | Auth with roles (Admin, Dentiste, Secretaire, Pharmacist) |
| Patient | Patient records (physique/morale entities) |
| Appointment | Scheduling with dentist assignment and status tracking |
| Treatment | Treatment records linked to patients |
| Invoice | Invoicing with OBR integration, tracks patient/insurance amounts |
| Stock | Product inventory with alerts and expiration tracking |
| MouvementStock | Stock transaction history |
| Caisse | Cash register management |

### Authorization

Role-based access via middleware:
- `admin` - Admin-only routes
- `canany:is-admin,is-pharmacist` - Multiple role check

User model methods: `isAdmin()`, `isDentiste()`, `isSecretaire()`, `isPharmacist()`

### OBR Integration

The system integrates with OBR (Office Burundais des Recettes) for fiscal invoicing:
- Models: `ObrPointer`, `ObrRequestBody`
- Invoice model has `getObrOrderFormatAttribute()` for API formatting
- Config via env: `OBR_USERNAME`, `OBR_PASSWORD`, `OBR_URL`

### Key Controllers

- `AdminController` (927 lines) - Dashboard stats, session management, revenue calculations
- `StockController` (596 lines) - Inventory CRUD, reports, import/export, alerts
- `AppointmentController` (446 lines) - Calendar, scheduling, status management

## Conventions

- **Language**: Mixed French/English (factures=invoices, rendezvous=appointments)
- **Status enums**: Confirme, Annule, Termine, En_attente, Reporte
- **Currency**: Decimal fields with 2 precision (total_amount, insurance_amount)
- **Soft deletes**: Used on User, Patient, Invoice, Stock models

## Route Structure

Main resource routes follow Laravel conventions:
- `/patients` - Patient management
- `/appointments` - Calendar and scheduling
- `/invoices` - Invoice CRUD with OBR sync
- `/stock` - Inventory management with analytics
- `/settings` - Treatment types, payment methods
- `/reports` - Monthly reports with Excel/PDF export
- `/admin` - Admin dashboard and session control
