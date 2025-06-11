<?php

/**
 * Dentist Controller
 *
 * This controller handles all dentist-related operations including CRUD operations
 * and relationship management with users.
 *
 * PHP version 8.1
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   Your Name <your.email@example.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/your-username/cabinet-dentaire
 */

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Dentist;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

/**
 * DentistController Class
 *
 * Handles all dentist-related operations in the dental clinic application.
 *
 * @category Controllers
 * @package  App\Http\Controllers
 * @author   Your Name <your.email@example.com>
 * @license  http://www.opensource.org/licenses/mit-license.php MIT License
 * @link     https://github.com/your-username/cabinet-dentaire
 */
class DentistController extends Controller
{
    /**
     * Display a listing of dentists
     *
     * @return View Returns a view with all dentists
     */
    public function index(): View
    {
        $query = Dentist::with('user')
            ->withCount('appointments');

        // if (request()->has('search') && request('search') != '') {
        //     $searchTerm = trim(request('search'));
        //     $parts = preg_split('/\s+/', $searchTerm, 5);

        //     $query->whereHas('user', function($q) use ($parts, $searchTerm) {
        //         if (count($parts) >= 2) {
        //             $q->where(function($sub) use ($parts) {
        //                 $sub->where('first_name', 'like', "%{$parts[0]}%")
        //                     ->where('last_name', 'like', "%{$parts[1]}%");
        //             })->orWhere(function($sub) use ($parts) {
        //                 $sub->where('first_name', 'like', "%{$parts[1]}%")
        //                     ->where('last_name', 'like', "%{$parts[0]}%");
        //             });
        //         } else {
        //             $q->where('first_name', 'like', "%{$searchTerm}%")
        //             ->orWhere('last_name', 'like', "%{$searchTerm}%")
        //             ->orWhere('email', 'like', "%{$searchTerm}%");
        //         }
        //     });
        // }

        // if (request()->has('specialty') && request('specialty') != '') {
        //     $query->where('specialty', request('specialty'));
        // }
        // if (request()->has('status') && request('status') != '') {
        //     $query->where('available', request('status'));
        // }

        $sortField = request('sort', 'id');
        $direction = request('direction', 'asc');

        if ($sortField === 'name') {
            $query->join('users', 'dentists.user_id', '=', 'users.id')
                ->orderBy('users.last_name', $direction)
                ->orderBy('users.first_name', $direction)
                ->select('dentists.*');
        } else {
            $query->orderBy($sortField, $direction);
        }

        $dentists = $query->paginate(15)->withQueryString();

        $specialties = Dentist::distinct('specialty')
            ->orderBy('specialty')
            ->pluck('specialty')
            ->filter();

        return view('dentist.index', compact('dentists', 'specialties'));
    }

    /**
     * Show the form for creating a new dentist
     *
     * @return View Returns the dentist creation form view
     */
    public function create(): View
    {
        $users = User::whereNotIn('id', function ($query) {
            $query->select('user_id')->from('dentists');
        })->where('role', 'Dentiste')->get();
        return view('dentist.create', compact('users'));
    }

    /**
     * Store a newly created dentist in storage
     *
     * @param Request $request The HTTP request containing dentist data
     *
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'specialty' => 'required|string|max:255',
            'license_number' => 'required|string|max:255|unique:dentists',
            'biography' => 'nullable|string',
            'calendar_color' => 'required|string|max:7',
            'available' => 'required|boolean',
        ]);

        Dentist::create($validatedData);

        return redirect()->route('dentists.index')
            ->with('success', 'Dentist created successfully.');
    }

    /**
     * Display the specified dentist
     *
     * @param Dentist $dentist The dentist to display
     *
     * @return View
     */
    public function show(Dentist $dentist): View
    {
        return view('dentist.show', compact('dentist'));
    }

    /**
     * Show the form for editing the specified dentist
     *
     * @param Dentist $dentist The dentist to edit
     *
     * @return View
     */
    public function edit(Dentist $dentist): View
    {
        $users = User::whereNotIn('id', function ($query) {
            $query->select('user_id')->from('dentists');
        })->where('role', 'Dentiste')->get();
        return view('dentist.edit', compact('dentist', 'users'));
    }

    /**
     * Update the specified dentist in storage
     *
     * @param Request $request The HTTP request containing updated data
     * @param Dentist $dentist The dentist to update
     *
     * @return RedirectResponse
     */
    public function update(Request $request, Dentist $dentist): RedirectResponse
    {

        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'specialty' => 'required|string|max:255',
            'license_number' => 'required|string|max:255|unique:dentists,license_number,'.
                $dentist->id,
            'biography' => 'nullable|string',
            'calendar_color' => 'required|string|max:7',
            'available' => 'required|boolean',
        ]);

        $dentist->update($validatedData);

        return redirect()->route('dentists.index')
            ->with('success', 'Dentist updated successfully.');
    }

    /**
     * Remove the specified dentist from storage
     *
     * @param Dentist $dentist The dentist to delete
     *
     * @return RedirectResponse
     */
    public function destroy(Dentist $dentist): RedirectResponse
    {
        $dentist->delete();

        return redirect()->route('dentists.index')
            ->with('success', 'Dentist deleted successfully.');
    }
}
