<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::query();

        // Search functionality
        if ($request->has('search') && $request->search != '') {
            $search = $request->get('search');
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Role filter
        if ($request->has('role') && $request->role != '') {
            $query->where('role', $request->role);
        }

        // Status filter
        if ($request->has('statut') && $request->statut != '') {
            $query->where('statut', $request->statut);
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('user.index', compact('users'));
    }

    public function create(): View
    {
        return view('user.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:Admin,Dentiste,Secretaire,Pharmacist',
            'phone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:20',
            'pays' => 'nullable|string|max:100',
            'secondary_phone' => 'nullable|string|max:20',
            'secondary_email' => 'nullable|email|max:255',
            'photo_url' => 'nullable|string|max:255',
            'statut' => 'required|in:Actif,Inactif',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $filename = 'user_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photoPath = $photo->storeAs('users/photos', $filename, 'public');
        }
        $validated['photo_url'] = $photoPath;
        $validated['password'] = Hash::make($validated['password']);
        $validated['name'] = $validated['first_name'] . ' ' . $validated['last_name'];

        User::create($validated);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur créé avec succès.');
    }

    public function show(User $user): View
    {
        return view('user.show', compact('user'));
    }

    public function edit(User $user): View
    {
        return view('user.edit', compact('user'));
    }

    public function update(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:Admin,Dentiste,Secretaire,Pharmacist',
            'phone' => 'nullable|string|max:20',
            'adresse' => 'nullable|string|max:255',
            'ville' => 'nullable|string|max:100',
            'code_postal' => 'nullable|string|max:20',
            'pays' => 'nullable|string|max:100',
            'secondary_phone' => 'nullable|string|max:20',
            'secondary_email' => 'nullable|email|max:255',
            'photo_url' => 'nullable|string|max:255',
            'statut' => 'required|in:Actif,Inactif',
        ]);

        $photoPath = $user->photo_url;
        if ($request->hasFile('photo')) {
            if ($user->photo_url && Storage::disk('public')->exists($user->photo_url)) {
                Storage::disk('public')->delete($user->photo_url);
            }

            $photo = $request->file('photo');
            $filename = 'user_' . $user->id . '_' . time() . '_' . uniqid() . '.' . $photo->getClientOriginalExtension();
            $photoPath = $photo->storeAs('users/photos', $filename, 'public');
        }
        $validated['photo_url'] = $photoPath;

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur mis à jour avec succès.');
    }

    public function destroy(User $user): RedirectResponse
    {
        if ($user->id === Auth::id()) {
            return redirect()->route('users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Utilisateur supprimé avec succès.');
    }
}
