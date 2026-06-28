<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\ProfileRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        return view('customer.profile', [
            'user' => auth()->user(),
        ]);
    }

    public function updateInfo(ProfileRequest $request): RedirectResponse
    {
        auth()->user()->update($request->validated());

        return redirect()->route('customer.profile.index')
            ->with('success', 'Profil mis a jour avec succes.');
    }

    public function updatePassword(ProfileRequest $request): RedirectResponse
    {
        auth()->user()->update([
            'password' => Hash::make($request->validated('password')),
        ]);

        return redirect()->route('customer.profile.index')
            ->with('success', 'Mot de passe modifie avec succes.');
    }

    public function delete(ProfileRequest $request): RedirectResponse
    {
        $user = auth()->user();

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Compte supprime avec succes.');
    }
}
