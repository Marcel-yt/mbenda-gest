<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::orderBy('created_at', 'desc')->paginate(15);
        return view('pages.app.admin.users.index', compact('users'));
    }

    // Affiche le formulaire de création
    public function create(): View
    {
        return view('pages.app.admin.users.create');
    }

    // Enregistre le nouvel utilisateur
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'first_name' => ['required','string','max:120'],
            'last_name'  => ['nullable','string','max:120'],
            'email'      => ['required','email','max:255','unique:users,email'],
            'role'       => ['required','in:admin,agent,client'],
            'password'   => ['required','string','min:8','confirmed'],
        ]);

        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name'  => $data['last_name'] ?? null,
            'email'      => $data['email'],
            'role'       => $data['role'],
            'password'   => Hash::make($data['password']),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé.');
    }
}