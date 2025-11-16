<?php
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Login / Logout
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard après login
use App\Models\Document;

Route::get('/dashboard', function() {
    $user = auth()->user();
    $isAdmin = $user->isAdmin();
    
    if ($isAdmin) {
        // Admin dashboard - optimized with single aggregation query
        $docCounts = Document::selectRaw('
            COUNT(*) as total,
            SUM(CASE WHEN MONTH(created_at) = ? THEN 1 ELSE 0 END) as monthly,
            SUM(CASE WHEN etat = "En attente" THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN etat = "Validé" THEN 1 ELSE 0 END) as validated,
            SUM(CASE WHEN etat = "Rejeté" THEN 1 ELSE 0 END) as rejected,
            SUM(CASE WHEN etat = "Archivé" THEN 1 ELSE 0 END) as archived
        ')->addBinding(now()->month, 'select')->first();
        
        $counts = [
            'documents' => $docCounts->total ?? 0,
            'users' => \App\Models\User::count(),
            'agents' => \App\Models\Agent::count(),
            'monthly_documents' => $docCounts->monthly ?? 0,
            'pending' => $docCounts->pending ?? 0,
            'validated' => $docCounts->validated ?? 0,
            'rejected' => $docCounts->rejected ?? 0,
            'archived' => $docCounts->archived ?? 0,
        ];
        
        // Get recent activities with optimized eager loading
        $recentActivities = \App\Models\DocumentHistory::with(['document.type', 'user', 'agent'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    } else {
        // User dashboard - optimized with single aggregation query
        $docCounts = Document::whereHas('agent', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->selectRaw('
                COUNT(*) as total,
                SUM(CASE WHEN MONTH(created_at) = ? THEN 1 ELSE 0 END) as monthly,
                SUM(CASE WHEN etat = "En attente" THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN etat = "Validé" THEN 1 ELSE 0 END) as validated,
                SUM(CASE WHEN etat = "Rejeté" THEN 1 ELSE 0 END) as rejected,
                SUM(CASE WHEN etat = "Archivé" THEN 1 ELSE 0 END) as archived
            ')->addBinding(now()->month, 'select')->first();
        
        $counts = [
            'documents' => $docCounts->total ?? 0,
            'pending' => $docCounts->pending ?? 0,
            'validated' => $docCounts->validated ?? 0,
            'rejected' => $docCounts->rejected ?? 0,
            'archived' => $docCounts->archived ?? 0,
            'monthly_documents' => $docCounts->monthly ?? 0,
        ];
        
        // Get recent activities for user's documents only
        $recentActivities = \App\Models\DocumentHistory::with(['document.type', 'user', 'agent'])
            ->whereHas('document.agent', function($q) use ($user) {
                $q->where('user_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
    }
    
    return view('dashboard', compact('counts', 'recentActivities', 'user'));
})->middleware('auth')->name('dashboard');

// Autres pages protégées
Route::middleware('auth')->group(function () {
    Route::get('/historique', function() {
        $user = auth()->user();
        $isAdmin = $user->isAdmin();
        
        // Optimized query with eager loading
        $query = \App\Models\DocumentHistory::with(['document.type', 'document.agent.user', 'user', 'agent'])
            ->orderBy('created_at', 'desc');
        
        // If not admin, only show user's documents history
        if (!$isAdmin) {
            $query->whereHas('document.agent', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        
        // Apply filters
        if (request('period')) {
            switch (request('period')) {
                case 'today':
                    $query->whereDate('created_at', today());
                    break;
                case 'week':
                    $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('created_at', now()->month);
                    break;
                case 'year':
                    $query->whereYear('created_at', now()->year);
                    break;
            }
        }
        
        if (request('action') && request('action') !== 'all') {
            $query->where('action', request('action'));
        }
        
        // Only admin can filter by agent
        if ($isAdmin && request('agent') && request('agent') !== 'all') {
            $query->where('agent_id', request('agent'));
        }
        
        $history = $query->paginate(20);
        
        return view('historique', compact('history'));
    })->name('historique');
    
    // Profile routes
    Route::get('/profile', function() {
        $user = auth()->user();
        $agent = $user->agent;
        return view('profile', compact('user', 'agent'));
    })->name('profile');
    
    // Settings route - redirects to admin settings for admins, profile for users
    Route::get('/settings', function() {
        $user = auth()->user();
        if ($user->isAdmin()) {
            return redirect()->route('admin.index');
        }
        return redirect()->route('profile');
    })->name('settings');
});

// Removed unused design page routes

// Page d'accueil - redirect to login if guest, otherwise to documents index/dashboard
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('documents.index');
    }
    return redirect()->route('login');
})->name('index');

// GED-related resources will be defined below (Documents, Archives, Movements, Agents, Notifications)
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\AgentController;
use App\Http\Controllers\DocumentTypeController;
use App\Http\Controllers\DirectionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ConfidentialityController;

        // Document Management CRUD Routes
        Route::middleware('auth')->group(function () {
            Route::resource('documents', DocumentController::class);
            Route::get('documents/search', [DocumentController::class, 'search'])->name('documents.search');
            
            // Document validation routes
            Route::put('documents/{document}/validate', [DocumentController::class, 'validate'])->name('documents.validate');
            Route::put('documents/{document}/reject', [DocumentController::class, 'reject'])->name('documents.reject');
            Route::put('documents/{document}/archive', [DocumentController::class, 'archive'])->name('documents.archive');
            Route::put('documents/{document}/resubmit', [DocumentController::class, 'resubmit'])->name('documents.resubmit');
            
            // Document sections
            Route::get('documents/pending', [DocumentController::class, 'pending'])->name('documents.pending');
            Route::get('documents/archives', [DocumentController::class, 'archives'])->name('documents.archives');
            Route::get('documents/rejected', [DocumentController::class, 'rejected'])->name('documents.rejected');
            
            // Users listing
            Route::get('users', function() {
                $users = \App\Models\User::with('agent')->paginate(20);
                return view('users.index', compact('users'));
            })->name('users.index');
            
            // Reset user password
            Route::put('users/{user}/reset-password', function(\App\Models\User $user, \Illuminate\Http\Request $request) {
                if (!auth()->user()->isAdmin()) {
                    return redirect()->back()->with('error', 'Accès non autorisé.');
                }
                
                $request->validate([
                    'password' => 'required|string|min:6|confirmed',
                ]);
                
                $user->update([
                    'password' => \Illuminate\Support\Facades\Hash::make($request->password),
                ]);
                
                return redirect()->route('users.index')->with('success', 'Mot de passe réinitialisé avec succès pour ' . $user->username);
            })->name('users.reset-password');
            
            // Document history
            Route::get('documents/{document}/history', function(Document $document) {
                $histories = $document->histories()->with(['user', 'agent'])->orderBy('created_at', 'desc')->get();
                return view('documents.history', compact('histories', 'document'));
            })->name('documents.history');
            
            // Notifications
            Route::post('notifications/{notification}/mark-read', function(\App\Models\Notification $notification) {
                if ($notification->user_id === auth()->id()) {
                    $notification->markAsRead();
                    return response()->json(['success' => true]);
                }
                return response()->json(['success' => false], 403);
            })->name('notifications.mark-read');
            
            Route::resource('agents', AgentController::class);
            Route::resource('document-types', DocumentTypeController::class);
            Route::resource('directions', DirectionController::class);
            Route::resource('services', ServiceController::class);
            Route::resource('confidentialities', ConfidentialityController::class);
            
            // Admin routes
            Route::prefix('admin')->name('admin.')->group(function () {
                Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('index');
                Route::get('/users', [\App\Http\Controllers\AdminController::class, 'users'])->name('users');
                Route::post('/users', [\App\Http\Controllers\AdminController::class, 'createUser'])->name('users.create');
                Route::put('/users/{user}', [\App\Http\Controllers\AdminController::class, 'updateUser'])->name('users.update');
                Route::delete('/users/{user}', [\App\Http\Controllers\AdminController::class, 'deleteUser'])->name('users.delete');
                
                Route::get('/document-types', [\App\Http\Controllers\AdminController::class, 'documentTypes'])->name('document-types');
                Route::post('/document-types', [\App\Http\Controllers\AdminController::class, 'createDocumentType'])->name('document-types.create');
                Route::put('/document-types/{type}', [\App\Http\Controllers\AdminController::class, 'updateDocumentType'])->name('document-types.update');
                Route::delete('/document-types/{type}', [\App\Http\Controllers\AdminController::class, 'deleteDocumentType'])->name('document-types.delete');
                
                Route::get('/security', [\App\Http\Controllers\AdminController::class, 'security'])->name('security');
                Route::post('/security', [\App\Http\Controllers\AdminController::class, 'updateSecurity'])->name('security.update');
            });
        });
