<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'matricule' => ['required','string'],
            'password' => ['required'],
        ]);

        // Find agent by matricule
        $agent = \App\Models\Agent::where('matricule', $credentials['matricule'])->first();
        
        if (!$agent || !$agent->user) {
            return back()->withInput($request->only('matricule'))->with('error', 'Matricule invalide.');
        }

        // Attempt login with user credentials
        if (Auth::attempt([
            'username' => $agent->user->username,
            'password' => $credentials['password']
        ], $request->filled('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('documents.index'));
        }

        return back()->withInput($request->only('matricule'))->with('error', 'Les identifiants sont invalides.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
