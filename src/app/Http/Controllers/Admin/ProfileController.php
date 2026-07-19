<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ProfileRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        return view('admin.profile', [
            'user' => auth()->user(),
        ]);
    }

    public function updateInfo(ProfileRequest $request): RedirectResponse
    {
        auth()->user()->update($request->validated());

        return redirect()->route('admin.profile.index')
            ->with('success', 'Profil mis a jour avec succès.');
    }

    public function updatePassword(ProfileRequest $request): RedirectResponse
    {
        auth()->user()->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        return redirect()->route('admin.profile.index')
            ->with('success', 'Mot de passe modifié avec succès.');
    }

    public function delete(ProfileRequest $request): RedirectResponse
    {
        if (User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('admin.profile.index')
                ->with('error', 'Impossible de supprimer le dernier admin.');
        }

        $user = auth()->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Compte admin supprimé avec succès.');
    }
}
