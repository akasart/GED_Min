<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Agent;
use App\Models\DocumentType;
use App\Models\Direction;
use App\Models\Service;
use App\Models\Confidentiality;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->user()->isAdmin()) {
                return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
            }
            return $next($request);
        });
    }

    /**
     * Show admin settings dashboard
     */
    public function index()
    {
        // Cache stats for 5 minutes as they don't change frequently
        $stats = \Cache::remember('admin_stats', 300, function() {
            return [
                'users' => User::count(),
                'agents' => Agent::count(),
                'document_types' => DocumentType::count(),
                'directions' => Direction::count(),
                'services' => Service::count(),
                'confidentialities' => Confidentiality::count(),
            ];
        });

        return view('admin.settings', compact('stats'));
    }

    /**
     * Show users management
     */
    public function users()
    {
        $users = User::with('agent')->paginate(15);
        return view('admin.users', compact('users'));
    }

    /**
     * Create new user
     */
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès.');
    }

    /**
     * Update user
     */
    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|in:admin,user',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès.');
    }

    /**
     * Delete user
     */
    public function deleteUser(User $user)
    {
        if ($user->id === auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé avec succès.');
    }

    /**
     * Show document types management
     */
    public function documentTypes()
    {
        $types = DocumentType::paginate(15);
        return view('admin.document-types', compact('types'));
    }

    /**
     * Create document type
     */
    public function createDocumentType(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:255|unique:document_types',
            'description' => 'nullable|string',
        ]);

        DocumentType::create($request->all());
        \Cache::forget('document_types');
        \Cache::forget('document_types_list');

        return redirect()->route('admin.document-types')->with('success', 'Type de document créé avec succès.');
    }

    /**
     * Update document type
     */
    public function updateDocumentType(Request $request, DocumentType $type)
    {
        $request->validate([
            'libelle' => ['required', 'string', 'max:255', Rule::unique('document_types')->ignore($type->id)],
            'description' => 'nullable|string',
        ]);

        $type->update($request->all());
        \Cache::forget('document_types');
        \Cache::forget('document_types_list');

        return redirect()->route('admin.document-types')->with('success', 'Type de document mis à jour avec succès.');
    }

    /**
     * Delete document type
     */
    public function deleteDocumentType(DocumentType $type)
    {
        if ($type->documents()->count() > 0) {
            return redirect()->back()->with('error', 'Ce type de document est utilisé par des documents existants.');
        }

        $type->delete();
        \Cache::forget('document_types');
        \Cache::forget('document_types_list');
        return redirect()->route('admin.document-types')->with('success', 'Type de document supprimé avec succès.');
    }

    /**
     * Show security settings
     */
    public function security()
    {
        return view('admin.security');
    }

    /**
     * Update security settings
     */
    public function updateSecurity(Request $request)
    {
        $request->validate([
            'session_timeout' => 'required|integer|min:30|max:480',
            'default_confidentiality' => 'required|exists:confidentialities,id',
            'max_file_size' => 'required|integer|min:1|max:50',
        ]);

        // Store settings in config or database
        // For now, we'll use a simple approach with session
        session([
            'security_settings' => [
                'session_timeout' => $request->session_timeout,
                'default_confidentiality' => $request->default_confidentiality,
                'max_file_size' => $request->max_file_size,
            ]
        ]);

        return redirect()->route('admin.security')->with('success', 'Paramètres de sécurité mis à jour avec succès.');
    }
}
