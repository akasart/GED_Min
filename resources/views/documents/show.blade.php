@extends('layouts.app')

@section('title', 'Fiche Document')

@section('content')
<div class="container-fluid" style="font-size: 0.875rem;">
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h5 style="font-size: 1.1rem; margin: 0;">Fiche Document</h5>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0" style="font-size: 0.8rem;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Documents</a></li>
        <li class="breadcrumb-item active">Détail</li>
      </ol>
    </nav>
  </div>

  <!-- Document Information Above -->
  <div class="card mb-3">
    <div class="card-header bg-light py-2">
      <h6 class="mb-0" style="font-size: 0.9rem;">Informations du document</h6>
    </div>
    <div class="card-body py-3">
      <div class="row">
        <div class="col-md-6 col-lg-3 mb-2">
          <div style="font-size: 0.8rem;">
            <strong style="font-size: 0.85rem;">Référence:</strong><br>
            <span class="text-primary" style="font-size: 0.85rem;">DOC-{{ now()->year }}-{{ str_pad($document->id, 3, '0', STR_PAD_LEFT) }}</span>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-2">
          <div style="font-size: 0.8rem;">
            <strong style="font-size: 0.85rem;">Date:</strong><br>
            <span style="font-size: 0.85rem;">{{ $document->formatted_date_creation }}</span>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-2">
          <div style="font-size: 0.8rem;">
            <strong style="font-size: 0.85rem;">Type:</strong><br>
            <span style="font-size: 0.85rem;">{{ $document->type ? $document->type->libelle : '-' }}</span>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-2">
          <div style="font-size: 0.8rem;">
            <strong style="font-size: 0.85rem;">État:</strong><br>
            @if($document->etat == 'Validé')
              <span class="badge badge-success badge-sm">Validé</span>
            @elseif($document->etat == 'En attente')
              <span class="badge badge-warning badge-sm">En attente</span>
            @elseif($document->etat == 'Rejeté')
              <span class="badge badge-danger badge-sm">Rejeté</span>
            @elseif($document->etat == 'Archivé')
              <span class="badge badge-info badge-sm">Archivé</span>
            @else
              <span class="badge badge-secondary badge-sm">{{ $document->etat ?? 'Nouveau' }}</span>
            @endif
          </div>
        </div>
      </div>
      <div class="row mt-2">
        <div class="col-12 mb-2">
          <div style="font-size: 0.8rem;">
            <strong style="font-size: 0.85rem;">Intitulé:</strong><br>
            <span style="font-size: 0.85rem;">{{ $document->titre }}</span>
          </div>
        </div>
        @if($document->agent)
        <div class="col-md-6 col-lg-3 mb-2">
          <div style="font-size: 0.8rem;">
            <strong style="font-size: 0.85rem;">Nom:</strong><br>
            <span style="font-size: 0.85rem;">{{ $document->agent->nom }}</span>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-2">
          <div style="font-size: 0.8rem;">
            <strong style="font-size: 0.85rem;">Prénom:</strong><br>
            <span style="font-size: 0.85rem;">{{ $document->agent->prenom }}</span>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-2">
          <div style="font-size: 0.8rem;">
            <strong style="font-size: 0.85rem;">Matricule:</strong><br>
            <span style="font-size: 0.85rem;">{{ $document->agent->matricule }}</span>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-2">
          <div style="font-size: 0.8rem;">
            <strong style="font-size: 0.85rem;">Ministère:</strong><br>
            <span style="font-size: 0.85rem;">{{ $document->agent->ministere_rattachement ?? '-' }}</span>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-2">
          <div style="font-size: 0.8rem;">
            <strong style="font-size: 0.85rem;">Direction:</strong><br>
            <span style="font-size: 0.85rem;">{{ $document->agent->direction ?? '-' }}</span>
          </div>
        </div>
        <div class="col-md-6 col-lg-3 mb-2">
          <div style="font-size: 0.8rem;">
            <strong style="font-size: 0.85rem;">Service:</strong><br>
            <span style="font-size: 0.85rem;">{{ $document->agent->service ?? '-' }}</span>
          </div>
        </div>
        @endif
        @if($document->observation)
        <div class="col-12 mb-2">
          <div style="font-size: 0.8rem;">
            <strong style="font-size: 0.85rem;">Observation:</strong><br>
            <span style="font-size: 0.85rem;">{{ $document->observation }}</span>
          </div>
        </div>
        @endif
        @if($document->motif_rejet)
        <div class="col-12 mb-2">
          <div style="font-size: 0.8rem;">
            <strong style="font-size: 0.85rem; color: #dc3545;">Motif de rejet:</strong><br>
            <span style="font-size: 0.85rem; color: #dc3545;">{{ $document->motif_rejet }}</span>
          </div>
        </div>
        @endif
      </div>
    </div>
  </div>

  <!-- Validation Actions for Admin -->
  @if(auth()->user()->isAdmin() && $document->isPending())
  <div class="card mb-3">
    <div class="card-header bg-warning py-2">
      <h6 class="mb-0" style="font-size: 0.9rem;">Actions de validation</h6>
    </div>
    <div class="card-body py-3">
      <div class="d-flex flex-wrap gap-2">
        <button class="btn btn-success btn-sm" onclick="validateDocument({{ $document->id }}, {{ json_encode($document->titre) }})" style="font-size: 0.85rem;">
          <i class="fas fa-check mr-1"></i>Valider
        </button>
        <button class="btn btn-danger btn-sm" onclick="showRejectModal({{ $document->id }}, {{ json_encode($document->titre) }})" style="font-size: 0.85rem;">
          <i class="fas fa-times mr-1"></i>Rejeter
        </button>
        <a href="{{ route('documents.history', $document) }}" class="btn btn-info btn-sm" style="font-size: 0.85rem;">
          <i class="fas fa-history mr-1"></i>Historique
        </a>
      </div>
    </div>
  </div>
  @endif

  <!-- Document Preview Under -->
  <div class="card mb-3">
    <div class="card-header bg-light py-2">
      <div class="d-flex justify-content-between align-items-center">
        <h6 class="mb-0" style="font-size: 0.9rem;">Aperçu du document</h6>
        <div>
          @if($document->fichier)
            <a href="{{ asset('storage/' . $document->fichier) }}" class="btn btn-primary btn-sm" target="_blank" style="font-size: 0.85rem;">
              <i class="fas fa-download mr-1"></i>Télécharger
            </a>
          @endif
          @if(!auth()->user()->isAdmin() && $document->isPending())
            <a href="{{ route('documents.edit', $document) }}" class="btn btn-warning btn-sm" style="font-size: 0.85rem;">
              <i class="fas fa-edit mr-1"></i>Modifier
            </a>
          @endif
          <a href="{{ route('documents.history', $document) }}" class="btn btn-info btn-sm" style="font-size: 0.85rem;">
            <i class="fas fa-history mr-1"></i>Historique
          </a>
          <a href="{{ route('documents.index') }}" class="btn btn-secondary btn-sm" style="font-size: 0.85rem;">
            <i class="fas fa-arrow-left mr-1"></i>Retour
          </a>
        </div>
      </div>
    </div>
    <div class="card-body py-3">
      <div class="document-preview">
        @if($document->fichier)
          @php
            $fileExtension = strtolower(pathinfo($document->fichier, PATHINFO_EXTENSION));
          @endphp
          
          @if($fileExtension == 'pdf')
            <div class="preview-container">
              <iframe src="{{ asset('storage/' . $document->fichier) }}#toolbar=1&navpanes=1&scrollbar=1" 
                      width="100%" 
                      height="800px" 
                      style="border: none; border-radius: 4px;"
                      type="application/pdf">
                <p>Votre navigateur ne supporte pas l'affichage des PDF. 
                  <a href="{{ asset('storage/' . $document->fichier) }}" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fas fa-download mr-1"></i>Télécharger le PDF
                  </a>
                </p>
              </iframe>
            </div>
            <div class="mt-2 text-center">
              <a href="{{ asset('storage/' . $document->fichier) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-external-link-alt mr-1"></i>Ouvrir dans un nouvel onglet
              </a>
            </div>
          @elseif(in_array($fileExtension, ['jpg', 'jpeg', 'png', 'gif']))
            <div class="preview-container text-center">
              <img src="{{ asset('storage/' . $document->fichier) }}" 
                   alt="Document Preview" 
                   class="img-fluid" 
                   style="max-height: 600px; border-radius: 4px;">
            </div>
          @else
            <div class="preview-placeholder">
              <div class="text-center py-4">
                <i class="fas fa-file-alt fa-3x text-muted mb-2"></i>
                <p class="text-muted mb-2" style="font-size: 0.85rem;">Aperçu non disponible</p>
                <p class="text-muted small" style="font-size: 0.75rem;">Ce type de fichier ne peut pas être prévisualisé</p>
                @if($document->fichier)
                  <a href="{{ asset('storage/' . $document->fichier) }}" class="btn btn-primary btn-sm mt-2" target="_blank" style="font-size: 0.85rem;">
                    <i class="fas fa-download mr-1"></i>Télécharger le fichier
                  </a>
                @endif
              </div>
            </div>
          @endif
        @else
          <div class="preview-placeholder">
            <div class="text-center py-4">
              <i class="fas fa-file-alt fa-3x text-muted mb-2"></i>
              <p class="text-muted mb-0" style="font-size: 0.85rem;">Aucun fichier attaché</p>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModalShow" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header py-2">
        <h6 class="modal-title" style="font-size: 0.9rem;">Rejeter le Document</h6>
        <button type="button" class="close" data-dismiss="modal" style="font-size: 1.2rem;">
          <span>&times;</span>
        </button>
      </div>
      <form id="rejectFormShow" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body py-3">
          <div class="alert alert-warning py-2" style="font-size: 0.85rem;">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Vous êtes sur le point de rejeter le document : <strong id="documentTitleRejectShow"></strong>
          </div>
          
          <div class="form-group mb-2">
            <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Motif du rejet *</label>
            <textarea class="form-control form-control-sm" name="motif_rejet" rows="3" required placeholder="Expliquez pourquoi ce document est rejeté..." style="font-size: 0.85rem;"></textarea>
            <small class="form-text text-muted" style="font-size: 0.75rem;">Ce motif sera visible par l'utilisateur qui a créé le document.</small>
          </div>
        </div>
        <div class="modal-footer py-2">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="font-size: 0.85rem;">Annuler</button>
          <button type="submit" class="btn btn-danger btn-sm" style="font-size: 0.85rem;">
            <i class="fas fa-times mr-1"></i>Rejeter le document
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<style>
.preview-container {
  background-color: #f8f9fa;
  border-radius: 4px;
  padding: 0.5rem;
  min-height: 400px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.preview-placeholder {
  background-color: #f8f9fa;
  border-radius: 4px;
  min-height: 400px;
  display: flex;
  align-items: center;
  justify-content: center;
}

.badge-sm {
  font-size: 0.7rem;
  padding: 0.25rem 0.5rem;
}

.card {
  box-shadow: 0 1px 3px rgba(0,0,0,0.1);
  border: none;
  margin-bottom: 1rem;
}

.card-header {
  background-color: #f8f9fa;
  border-bottom: 1px solid #dee2e6;
}

.gap-2 {
  gap: 0.5rem;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .preview-container iframe {
    height: 400px !important;
  }
  
  .card-body {
    padding: 0.75rem !important;
  }
  
  .btn-sm {
    font-size: 0.8rem;
    padding: 0.25rem 0.5rem;
  }
}

@media (max-width: 576px) {
  .preview-container iframe {
    height: 300px !important;
  }
  
  .d-flex.gap-2 {
    flex-direction: column;
  }
  
  .d-flex.gap-2 .btn {
    width: 100%;
    margin-bottom: 0.25rem;
  }
}
</style>

<script>
function validateDocument(documentId, title) {
  const safeTitle = title.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
  if (confirm('Êtes-vous sûr de vouloir valider le document "' + safeTitle + '" ?')) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/documents/' + documentId + '/validate';
    
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'PUT';
    
    form.appendChild(csrfToken);
    form.appendChild(methodField);
    document.body.appendChild(form);
    form.submit();
  }
}

function showRejectModal(documentId, title) {
  const titleElement = document.getElementById('documentTitleRejectShow');
  if (titleElement) {
    titleElement.textContent = title;
  }
  const formElement = document.getElementById('rejectFormShow');
  if (formElement) {
    formElement.action = '/documents/' + documentId + '/reject';
  }
  
  // Wait for jQuery to be available
  if (typeof jQuery !== 'undefined') {
    jQuery('#rejectModalShow').modal('show');
  } else {
    // Fallback if jQuery is not loaded
    const modal = document.getElementById('rejectModalShow');
    if (modal) {
      modal.style.display = 'block';
      modal.classList.add('show');
    }
  }
}

// Wait for jQuery to load
document.addEventListener('DOMContentLoaded', function() {
  // Check if jQuery is loaded, if not wait a bit
  function waitForJQuery() {
    if (typeof jQuery !== 'undefined') {
      // jQuery is loaded
      return;
    }
    setTimeout(waitForJQuery, 100);
  }
  waitForJQuery();
});
</script>
@endsection
