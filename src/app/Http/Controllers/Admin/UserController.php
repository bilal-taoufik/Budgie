<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UserRequest;
use App\Mail\VerifyMail;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::latest()->get();

        return view('admin.users', compact('users'));
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'firstname' => $validated['firstname'],
            'lastname' => $validated['lastname'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'admin',
            'email_verified' => false,
            'email_verification_token' => bin2hex(random_bytes(32)),
            'email_verification_expires_at' => now()->addHours(24),
        ]);

        $verificationUrl = route('verify.email', [
            'token' => $user->email_verification_token,
        ]);

        Mail::to($user->email)->send(new WelcomeMail($user));
        Mail::to($user->email)->send(new VerifyMail($user, $verificationUrl));

        return redirect()->route('admin.users.index')
            ->with('success', 'Admin ajoute avec succes. Un e-mail de verification lui a ete envoye.');
    }

    public function delete(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Vous ne pouvez pas supprimer votre propre compte ici.');
        }

        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Impossible de supprimer le dernier admin.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Utilisateur supprime avec succes.');
    }
}
