<?php
namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentHistory;
use App\Models\DocumentType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the documents with optional filters.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->isAdmin();
        
        // Optimized query with all necessary relationships
        $query = Document::with(['agent.user', 'type', 'creator', 'validator', 'rejector']);
        
        // Si l'utilisateur n'est pas admin, il ne voit que ses propres documents
        if (!$isAdmin) {
            $query->whereHas('agent', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        
        // Apply filters
        if ($request->filled('search')) {
            $query->where('titre', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('type')) {
            $query->where('document_type_id', $request->type);
        }

        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }

        $documents = $query->latest()->paginate(15);
        
        // Cache document types (rarely change)
        $types = \Cache::remember('document_types', 3600, function() {
            return DocumentType::all();
        });
        
        // Optimize counts - use single query with conditional aggregation
        $baseQuery = Document::query();
        if (!$isAdmin) {
            $baseQuery->whereHas('agent', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        
        // Get all counts in one query using conditional aggregation
        $counts = $baseQuery->selectRaw('
            SUM(CASE WHEN etat = "En attente" THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN etat = "Validé" THEN 1 ELSE 0 END) as validated,
            SUM(CASE WHEN etat = "Rejeté" THEN 1 ELSE 0 END) as rejected,
            SUM(CASE WHEN etat = "Archivé" THEN 1 ELSE 0 END) as archived
        ')->first();
        
        $pendingCount = $counts->pending ?? 0;
        $validatedCount = $counts->validated ?? 0;
        $rejectedCount = $counts->rejected ?? 0;
        $archivedCount = $counts->archived ?? 0;
        
        return view('documents.index', compact('documents', 'types', 'pendingCount', 'validatedCount', 'rejectedCount', 'archivedCount'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        
        if (empty($query)) {
            return redirect()->route('documents.index');
        }
        
        $baseQuery = Document::with(['agent', 'type', 'creator', 'validator', 'rejector']);
        
        // Si l'utilisateur n'est pas admin, il ne voit que ses propres documents
        if (!auth()->user()->isAdmin()) {
            $baseQuery->whereHas('agent', function($q) {
                $q->where('user_id', auth()->id());
            });
        }
        
        $documents = $baseQuery
            ->where(function($q) use ($query) {
                $q->where('titre', 'like', "%{$query}%")
                  ->orWhere('observation', 'like', "%{$query}%")
                  ->orWhereHas('agent', function($agentQuery) use ($query) {
                      $agentQuery->where('nom', 'like', "%{$query}%")
                                ->orWhere('prenom', 'like', "%{$query}%")
                                ->orWhere('matricule', 'like', "%{$query}%");
                  })
                  ->orWhereHas('type', function($typeQuery) use ($query) {
                      $typeQuery->where('libelle', 'like', "%{$query}%");
                  });
            })
            ->latest()
            ->paginate(15);
        
        // Optimize counts - use single query with conditional aggregation
        $user = auth()->user();
        $isAdmin = $user->isAdmin();
        
        $countsQuery = Document::query();
        if (!$isAdmin) {
            $countsQuery->whereHas('agent', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        
        $counts = $countsQuery->selectRaw('
            SUM(CASE WHEN etat = "En attente" THEN 1 ELSE 0 END) as pending,
            SUM(CASE WHEN etat = "Validé" THEN 1 ELSE 0 END) as validated,
            SUM(CASE WHEN etat = "Rejeté" THEN 1 ELSE 0 END) as rejected,
            SUM(CASE WHEN etat = "Archivé" THEN 1 ELSE 0 END) as archived
        ')->first();
        
        $pendingCount = $counts->pending ?? 0;
        $validatedCount = $counts->validated ?? 0;
        $rejectedCount = $counts->rejected ?? 0;
        $archivedCount = $counts->archived ?? 0;
        
        // Cache document types
        $types = \Cache::remember('document_types', 3600, function() {
            return DocumentType::all();
        });
        
        return view('documents.index', compact('documents', 'query', 'types', 'pendingCount', 'validatedCount', 'rejectedCount', 'archivedCount'));
    }

    public function create()
    {
        // Cache these as they rarely change
        $types = \Cache::remember('document_types', 3600, function() {
            return \App\Models\DocumentType::all();
        });
        $directions = \Cache::remember('directions', 3600, function() {
            return \App\Models\Direction::all();
        });
        $services = \Cache::remember('services', 3600, function() {
            return \App\Models\Service::with('direction')->get();
        });
        return view('documents.create', compact('types','directions','services'));
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $agent = $user->agent;

        if (!$agent) {
            return redirect()->back()->with('error', 'Vous devez être un agent pour créer un document.');
        }

        $data = $request->validate([
            'titre' => 'required|string|max:150',
            'fichier' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png', // 10MB
            'date_document' => 'required|date',
            'observation' => 'nullable|string',
        ]);

        // Auto-detect document type from file extension
        $file = $request->file('fichier');
        $extension = strtolower($file->getClientOriginalExtension());
        $documentType = $this->detectDocumentTypeFromExtension($extension);

        // Create document
        $document = Document::create([
            'titre' => $data['titre'],
            'fichier' => $file->store('documents', 'public'),
            'agent_id' => $agent->id,
            'document_type_id' => $documentType ? $documentType->id : null,
            'etat' => 'En attente',
            'observation' => $data['observation'],
            'created_by' => $user->id,
        ]);

        // Notify all admins about pending document (optimized - get IDs only)
        $adminIds = \App\Models\User::where('role', 'admin')->pluck('id');
        foreach ($adminIds as $adminId) {
            $this->createNotification(
                $adminId,
                'Nouveau document en attente',
                "Un nouveau document '{$document->titre}' créé par {$agent->nom} {$agent->prenom} est en attente de validation."
            );
        }

        return redirect()->route('documents.index')->with('success', 'Document créé.');
    }

    public function show(Document $document)
    {
        // Load all necessary relationships
        $document->load(['agent.user', 'type', 'creator', 'validator', 'rejector', 'histories.user']);
        return view('documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        return view('documents.edit', compact('document'));
    }

    public function update(Request $request, Document $document)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:150',
            'fichier' => 'nullable|file',
            'document_type_id' => 'required|integer',
            'agent_id' => 'required|integer',
        ]);

        if ($request->hasFile('fichier')) {
            // remove old file
            if ($document->fichier) { Storage::disk('public')->delete($document->fichier); }
            $data['fichier'] = $request->file('fichier')->store('documents', 'public');
        }

        $document->update($data);
        return redirect()->route('documents.index')->with('success', 'Document mis à jour.');
    }

    public function destroy(Document $document)
    {
        if ($document->fichier) { Storage::disk('public')->delete($document->fichier); }
        $document->delete();
        return redirect()->route('documents.index')->with('success', 'Document supprimé.');
    }

    /**
     * Validate a document (Admin only)
     */
    public function validate(Request $request, Document $document)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        $document->update([
            'etat' => 'Validé',
            'validated_by' => auth()->id(),
            'validated_at' => now(),
            'rejected_by' => null,
            'rejected_at' => null,
            'motif_rejet' => null,
        ]);

        // Log history
        DocumentHistory::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'agent_id' => $document->agent_id,
            'action' => 'validated',
            'description' => 'Document validé: ' . $document->titre,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Create notification for document creator
        $this->createNotification(
            $document->agent->user_id,
            'Document validé',
            "Votre document '{$document->titre}' a été validé."
        );

        return redirect()->back()->with('success', 'Document validé avec succès.');
    }

    /**
     * Reject a document (Admin only)
     */
    public function reject(Request $request, Document $document)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        $request->validate([
            'motif_rejet' => 'required|string|max:500'
        ]);

        $document->update([
            'etat' => 'Rejeté',
            'rejected_by' => auth()->id(),
            'rejected_at' => now(),
            'motif_rejet' => $request->motif_rejet,
            'validated_by' => null,
            'validated_at' => null,
        ]);

        // Log history
        DocumentHistory::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'agent_id' => $document->agent_id,
            'action' => 'rejected',
            'description' => 'Document rejeté: ' . $document->titre . ' - Motif: ' . $request->motif_rejet,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Create notification for document creator
        $this->createNotification(
            $document->agent->user_id,
            'Document rejeté',
            "Votre document '{$document->titre}' a été rejeté. Motif: {$request->motif_rejet}"
        );

        return redirect()->back()->with('success', 'Document rejeté avec succès.');
    }

    /**
     * Archive a document (Admin only)
     */
    public function archive(Document $document)
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->back()->with('error', 'Accès non autorisé.');
        }

        if (!$document->isValidated()) {
            return redirect()->back()->with('error', 'Seuls les documents validés peuvent être archivés.');
        }

        $document->update(['etat' => 'Archivé']);

        // Log history
        DocumentHistory::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'agent_id' => $document->agent_id,
            'action' => 'archived',
            'description' => 'Document archivé: ' . $document->titre,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        return redirect()->back()->with('success', 'Document archivé avec succès.');
    }

    /**
     * Get pending documents (Admin only)
     */
    public function pending()
    {
        if (!auth()->user()->isAdmin()) {
            return redirect()->route('documents.index')->with('error', 'Accès non autorisé.');
        }

        $documents = Document::with(['agent.user', 'type', 'creator'])
            ->where('etat', 'En attente')
            ->latest()
            ->paginate(15);

        return view('documents.pending', compact('documents'));
    }

    /**
     * Get validated documents (Archives)
     */
    public function archives()
    {
        $user = auth()->user();
        $isAdmin = $user->isAdmin();
        
        $query = Document::with(['agent.user', 'type', 'validator'])
            ->where('etat', 'Validé');
        
        // Si l'utilisateur n'est pas admin, il ne voit que ses propres documents
        if (!$isAdmin) {
            $query->whereHas('agent', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        
        $documents = $query->latest()->paginate(15);
        return view('documents.archives', compact('documents'));
    }

    /**
     * Display rejected documents
     */
    public function rejetes()
    {
        $documents = Document::where('etat', 'rejete')->get();
        return view('documents.rejetes', compact('documents'));
    }

    /**
     * Get rejected documents
     */
    public function rejected()
    {
        $user = auth()->user();
        $isAdmin = $user->isAdmin();
        
        $query = Document::with(['agent.user', 'type', 'rejector'])
            ->where('etat', 'Rejeté');
        
        // Si l'utilisateur n'est pas admin, il ne voit que ses propres documents
        if (!$isAdmin) {
            $query->whereHas('agent', function($q) use ($user) {
                $q->where('user_id', $user->id);
            });
        }
        
        $documents = $query->latest()->paginate(15);
        return view('documents.rejected', compact('documents'));
    }

    /**
     * Resubmit a rejected document
     */
    public function resubmit(Request $request, Document $document)
    {
        if (!$document->isRejected()) {
            return redirect()->back()->with('error', 'Seuls les documents rejetés peuvent être renvoyés.');
        }

        // Vérifier que l'utilisateur est le créateur du document
        if ($document->agent->user_id !== auth()->id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez renvoyer que vos propres documents.');
        }

        $data = $request->validate([
            'titre' => 'required|string|max:150',
            'fichier' => 'nullable|file',
            'observation' => 'nullable|string',
        ]);

        if ($request->hasFile('fichier')) {
            // remove old file
            if ($document->fichier) { Storage::disk('public')->delete($document->fichier); }
            $data['fichier'] = $request->file('fichier')->store('documents', 'public');
        }

        $document->update([
            'etat' => 'En attente',
            'motif_rejet' => null,
            'rejected_by' => null,
            'rejected_at' => null,
            'validated_by' => null,
            'validated_at' => null,
        ] + $data);

        // Log history
        DocumentHistory::create([
            'document_id' => $document->id,
            'user_id' => auth()->id(),
            'agent_id' => $document->agent_id,
            'action' => 'resubmitted',
            'description' => 'Document renvoyé: ' . $document->titre,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Notify all admins about resubmitted document (optimized - get IDs only)
        $adminIds = \App\Models\User::where('role', 'admin')->pluck('id');
        foreach ($adminIds as $adminId) {
            $this->createNotification(
                $adminId,
                'Document renvoyé en attente',
                "Le document '{$document->titre}' a été renvoyé par {$document->agent->nom} {$document->agent->prenom} et est en attente de validation."
            );
        }

        return redirect()->route('documents.rejected')->with('success', 'Document renvoyé avec succès.');
    }

    /**
     * Create notification helper
     */
    private function createNotification($userId, $title, $message)
    {
        \App\Models\Notification::create([
            'user_id' => $userId,
            'titre' => $title,
            'message' => $message,
            'date_notification' => now(),
            'is_read' => false,
        ]);
    }

    /**
     * Detect document type from file extension
     */
    private function detectDocumentTypeFromExtension($extension)
    {
        // Mapping of extensions to document type names
        $extensionMap = [
            'pdf' => 'PDF',
            'doc' => 'Document Word',
            'docx' => 'Document Word',
            'xls' => 'Tableur Excel',
            'xlsx' => 'Tableur Excel',
            'jpg' => 'Image',
            'jpeg' => 'Image',
            'png' => 'Image',
        ];

        $typeName = $extensionMap[$extension] ?? null;
        
        if ($typeName) {
            // Try to find existing document type
            $documentType = \App\Models\DocumentType::where('libelle', 'like', '%' . $typeName . '%')->first();
            
            // If not found, create it
            if (!$documentType) {
                $documentType = \App\Models\DocumentType::create([
                    'libelle' => $typeName,
                    'description' => 'Type détecté automatiquement depuis l\'extension .' . $extension,
                ]);
                // Invalidate cache when new type is created
                \Cache::forget('document_types');
                \Cache::forget('document_types_list');
            }
            
            return $documentType;
        }

        return null;
    }
}
