<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('profil', ['user' => $request->user()]);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'nom' => ['required', 'string', 'max:50', "regex:/^[\pL\s'-]+$/u"],
            'prenom' => ['required', 'string', 'max:50', "regex:/^[\pL\s'-]+$/u"],
            'email' => [
                'required',
                'email',
                'max:100',
                Rule::unique('client', 'email')->ignore($user->getKey()),
            ],
            'tel' => ['required', 'string', 'max:20', 'regex:/^\+?[0-9 .()-]{8,20}$/'],
        ]);

        $emailChanged = $user->email !== $validated['email'];

        $user->fill([
            'nom' => $validated['nom'],
            'prenom' => $validated['prenom'],
            'email' => $validated['email'],
            'tel' => $validated['tel'],
        ]);

        if ($emailChanged) {
            $user->email_verified_at = null;
        }

        $user->save();

        return back()->with('status', 'profile-updated');
    }
}
