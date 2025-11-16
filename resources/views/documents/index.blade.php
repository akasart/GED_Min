@extends('layouts.app')

@section('title', 'Tous les Documents')

@section('content')
<div class="container-fluid" style="font-size: 0.875rem;">
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
    <h5 style="font-size: 1.1rem; margin: 0;">
      @if(isset($query))
        Résultats pour "{{ $query }}"
      @else
        Mes Documents
      @endif
    </h5>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0" style="font-size: 0.8rem;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item active">Documents</li>
      </ol>
    </nav>
  </div>

  <!-- Search and Actions Section -->
  <div class="card mb-3">
    <div class="card-body py-2">
      <div class="row align-items-center">
        <div class="col-md-6 mb-2 mb-md-0">
          <form action="{{ route('documents.search') }}" method="GET">
            <div class="input-group input-group-sm">
              <input type="text" class="form-control" name="q" placeholder="Rechercher..." value="{{ request('q') }}" style="font-size: 0.85rem;">
              <div class="input-group-append">
                <button class="btn btn-primary btn-sm" type="submit" style="font-size: 0.85rem;">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
        <div class="col-md-6 text-right">
          <a href="{{ route('documents.create') }}" class="btn btn-success btn-sm" style="font-size: 0.85rem;">
            <i class="fas fa-plus mr-1"></i>Ajouter
          </a>
          <a href="{{ route('documents.archives') }}" class="btn btn-info btn-sm ml-2" style="font-size: 0.85rem;">
            <i class="fas fa-archive mr-1"></i>Archives
          </a>
        </div>
      </div>
    </div>
  </div>

  <!-- Filters Section with Tabs -->
  <ul class="nav nav-tabs mb-3" id="documentTabs" role="tablist" style="font-size: 0.85rem;">
    <li class="nav-item" role="presentation">
      <a class="nav-link {{ !request('etat') ? 'active' : '' }}" id="all-tab" href="{{ route('documents.index') }}" role="tab" aria-controls="all" aria-selected="{{ !request('etat') ? 'true' : 'false' }}">
        <i class="fas fa-list mr-1"></i>Tous
      </a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link {{ request('etat') == 'En attente' ? 'active' : '' }}" id="pending-tab" href="{{ route('documents.index', ['etat' => 'En attente']) }}" role="tab" aria-controls="pending" aria-selected="{{ request('etat') == 'En attente' ? 'true' : 'false' }}">
        <i class="fas fa-hourglass-half mr-1"></i>En attente <span class="badge badge-warning ml-1">{{ $pendingCount ?? 0 }}</span>
      </a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link {{ request('etat') == 'Validé' ? 'active' : '' }}" id="validated-tab" href="{{ route('documents.index', ['etat' => 'Validé']) }}" role="tab" aria-controls="validated" aria-selected="{{ request('etat') == 'Validé' ? 'true' : 'false' }}">
        <i class="fas fa-check-circle mr-1"></i>Validé <span class="badge badge-success ml-1">{{ $validatedCount ?? 0 }}</span>
      </a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link {{ request('etat') == 'Rejeté' ? 'active' : '' }}" id="rejected-tab" href="{{ route('documents.index', ['etat' => 'Rejeté']) }}" role="tab" aria-controls="rejected" aria-selected="{{ request('etat') == 'Rejeté' ? 'true' : 'false' }}">
        <i class="fas fa-times-circle mr-1"></i>Rejeté <span class="badge badge-danger ml-1">{{ $rejectedCount ?? 0 }}</span>
      </a>
    </li>
    <li class="nav-item" role="presentation">
      <a class="nav-link {{ request('etat') == 'Archivé' ? 'active' : '' }}" id="archived-tab" href="{{ route('documents.index', ['etat' => 'Archivé']) }}" role="tab" aria-controls="archived" aria-selected="{{ request('etat') == 'Archivé' ? 'true' : 'false' }}">
        <i class="fas fa-archive mr-1"></i>Archivé <span class="badge badge-info ml-1">{{ $archivedCount ?? 0 }}</span>
      </a>
    </li>
  </ul>

  <!-- Advanced Filters Card -->
  <div class="card mb-3">
    <div class="card-body py-2">
      <form action="{{ route('documents.index') }}" method="GET">
        <div class="row align-items-center">
          <div class="col-md-4 mb-2">
            <input type="text" class="form-control form-control-sm" name="search" placeholder="Rechercher par titre..." value="{{ request('search') }}">
          </div>
          <div class="col-md-3 mb-2">
            <select class="form-control form-control-sm" name="type">
              <option value="">-- Type de document --</option>
              @foreach($types as $type)
                <option value="{{ $type->id }}" {{ request('type') == $type->id ? 'selected' : '' }}>{{ $type->libelle }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3 mb-2">
            <select class="form-control form-control-sm" name="etat">
              <option value="">-- État --</option>
              <option value="Validé" {{ request('etat') == 'Validé' ? 'selected' : '' }}>Validé</option>
              <option value="Rejeté" {{ request('etat') == 'Rejeté' ? 'selected' : '' }}>Rejeté</option>
              <option value="En attente" {{ request('etat') == 'En attente' ? 'selected' : '' }}>En attente</option>
              <option value="Archivé" {{ request('etat') == 'Archivé' ? 'selected' : '' }}>Archivé</option>
            </select>
          </div>
          <div class="col-md-2 text-right">
            <button type="submit" class="btn btn-primary btn-sm">
              <i class="fas fa-filter mr-1"></i>Filtrer
            </button>
            <a href="{{ route('documents.index') }}" class="btn btn-secondary btn-sm">
              <i class="fas fa-redo mr-1"></i>Réinitialiser
            </a>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Documents Table -->
  <div class="card">
    <div class="card-body p-0">
      @if($documents->isEmpty())
        <div class="text-center py-4">
          <i class="fas fa-folder-open fa-2x text-muted mb-2" style="font-size: 1.5rem;"></i>
          <p class="text-muted mb-0" style="font-size: 0.85rem;">Aucun document trouvé.</p>
        </div>
      @else
        <div class="table-responsive">
          <table class="table table-hover mb-0" style="font-size: 0.85rem;">
            <thead class="thead-light">
              <tr>
                <th style="font-size: 0.8rem; padding: 0.5rem;">Réf.</th>
                <th style="font-size: 0.8rem; padding: 0.5rem;">Intitulé</th>
                <th style="font-size: 0.8rem; padding: 0.5rem;">Type</th>
                <th style="font-size: 0.8rem; padding: 0.5rem;" class="d-none d-md-table-cell">Direction</th>
                <th style="font-size: 0.8rem; padding: 0.5rem;" class="d-none d-lg-table-cell">Date</th>
                <th style="font-size: 0.8rem; padding: 0.5rem;">Statut</th>
                <th style="font-size: 0.8rem; padding: 0.5rem;">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($documents as $doc)
                <tr>
                  <td style="padding: 0.5rem;">
                    <span class="font-weight-bold text-primary" style="font-size: 0.8rem;">DOC-{{ now()->year }}-{{ str_pad($doc->id, 3, '0', STR_PAD_LEFT) }}</span>
                  </td>
                  <td style="padding: 0.5rem;">
                    <div style="font-size: 0.85rem; font-weight: 500;">{{ Str::limit($doc->titre, 30) }}</div>
                  </td>
                  <td style="padding: 0.5rem;">
                    <div class="d-flex align-items-center">
                      @if($doc->type)
                        <span style="font-size: 0.8rem;">{{ $doc->type->libelle }}</span>
                      @else
                        <span style="font-size: 0.8rem;">-</span>
                      @endif
                    </div>
                  </td>
                  <td style="padding: 0.5rem;" class="d-none d-md-table-cell">
                    <span class="text-muted" style="font-size: 0.8rem;">{{ $doc->agent ? Str::limit($doc->agent->direction, 20) : '-' }}</span>
                  </td>
                  <td style="padding: 0.5rem;" class="d-none d-lg-table-cell">
                    <span class="text-muted" style="font-size: 0.8rem;">{{ $doc->formatted_date_creation }}</span>
                  </td>
                  <td style="padding: 0.5rem;">
                    @if($doc->etat == 'Validé')
                      <span class="badge badge-success badge-sm">Validé</span>
                    @elseif($doc->etat == 'En attente')
                      <span class="badge badge-warning badge-sm">En attente</span>
                    @elseif($doc->etat == 'Rejeté')
                      <span class="badge badge-danger badge-sm">Rejeté</span>
                    @elseif($doc->etat == 'Archivé')
                      <span class="badge badge-info badge-sm">Archivé</span>
                    @else
                      <span class="badge badge-secondary badge-sm">{{ $doc->etat ?? 'Nouveau' }}</span>
                    @endif
                  </td>
                  <td style="padding: 0.5rem;">
                    <div class="btn-group btn-group-sm" role="group">
                      <a href="{{ route('documents.show', $doc) }}" class="btn btn-outline-primary btn-sm" title="Voir" style="font-size: 0.75rem; padding: 0.2rem 0.4rem;">
                        <i class="fas fa-eye"></i>
                      </a>
                      @if($doc->fichier)
                        <a href="{{ asset('storage/' . $doc->fichier) }}" class="btn btn-outline-success btn-sm" title="Télécharger" target="_blank" style="font-size: 0.75rem; padding: 0.2rem 0.4rem;">
                          <i class="fas fa-download"></i>
                        </a>
                      @endif
                      
                      @if(auth()->user()->isAdmin())
                        @if($doc->isPending())
                          <button class="btn btn-success btn-sm" title="Valider" onclick="validateDocument({{ $doc->id }}, {{ json_encode($doc->titre) }})" style="font-size: 0.75rem; padding: 0.2rem 0.4rem;">
                            <i class="fas fa-check"></i>
                          </button>
                          <button class="btn btn-danger btn-sm" title="Rejeter" onclick="showRejectModal({{ $doc->id }}, {{ json_encode($doc->titre) }})" style="font-size: 0.75rem; padding: 0.2rem 0.4rem;">
                            <i class="fas fa-times"></i>
                          </button>
                        @elseif($doc->isValidated())
                          <button class="btn btn-info btn-sm" title="Archiver" onclick="archiveDocument({{ $doc->id }}, {{ json_encode($doc->titre) }})" style="font-size: 0.75rem; padding: 0.2rem 0.4rem;">
                            <i class="fas fa-archive"></i>
                          </button>
                        @endif
                      @else
                        @if($doc->isRejected())
                          <button class="btn btn-warning btn-sm" title="Corriger" onclick="showResubmitModal({{ $doc->id }}, {{ json_encode($doc->titre) }}, {{ json_encode($doc->motif_rejet ?? '') }})" style="font-size: 0.75rem; padding: 0.2rem 0.4rem;">
                            <i class="fas fa-redo"></i>
                          </button>
                        @elseif($doc->isPending())
                          <a href="{{ route('documents.edit', $doc) }}" class="btn btn-outline-warning btn-sm" title="Modifier" style="font-size: 0.75rem; padding: 0.2rem 0.4rem;">
                            <i class="fas fa-edit"></i>
                          </a>
                        @endif
                      @endif
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  </div>

  <!-- Pagination -->
  @if($documents->hasPages())
    <div class="card mt-3">
      <div class="card-body py-3">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
          <div class="text-muted" style="font-size: 0.85rem; margin-bottom: 1rem;">
            <strong>Affichage:</strong> {{ $documents->firstItem() ?? 0 }}-{{ $documents->lastItem() ?? 0 }} sur {{ $documents->total() }} documents
            <span class="text-muted ml-2">
              (Page {{ $documents->currentPage() }} sur {{ $documents->lastPage() }})
            </span>
          </div>
          <div style="margin-bottom: 1rem;">
            {{ $documents->links('pagination::bootstrap-4') }}
          </div>
        </div>
      </div>
    </div>
  @else
    @if($documents->total() > 0)
      <div class="alert alert-info mt-3" style="font-size: 0.85rem;">
        <i class="fas fa-info-circle mr-2"></i>
        <strong>{{ $documents->total() }}</strong> document(s) affiché(s)
      </div>
    @endif
  @endif
</div>

<style>
.table th {
  border-top: none;
  font-weight: 600;
  color: #495057;
  background-color: #f8f9fa;
  font-size: 0.8rem;
}
.table td {
  vertical-align: middle;
  border-top: 1px solid #dee2e6;
}
.badge-sm {
  font-size: 0.7rem;
  padding: 0.25rem 0.5rem;
}
.btn-group .btn {
  margin-right: 2px;
}
.btn-group .btn:last-child {
  margin-right: 0;
}

/* Responsive table */
@media (max-width: 768px) {
  .table {
    font-size: 0.8rem;
  }
  
  .btn-group .btn {
    padding: 0.15rem 0.3rem;
    font-size: 0.7rem;
  }
}
</style>

<!-- Modals -->
<!-- Reject Modal -->
<div class="modal fade" id="rejectModalIndex" tabindex="-1" role="dialog" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header py-2">
        <h6 class="modal-title" id="rejectModalLabel" style="font-size: 0.9rem;">Rejeter le Document</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 1.2rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="rejectFormIndex" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body py-3">
          <div class="alert alert-warning py-2" style="font-size: 0.85rem;">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Vous êtes sur le point de rejeter le document : <strong id="documentTitleRejectIndex"></strong>
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

<!-- Resubmit Modal -->
<div class="modal fade" id="resubmitModalIndex" tabindex="-1" role="dialog" aria-labelledby="resubmitModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header py-2">
        <h6 class="modal-title" id="resubmitModalLabel" style="font-size: 0.9rem;">Corriger et Renvoyer le Document</h6>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="font-size: 1.2rem;">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="resubmitFormIndex" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body py-3">
          <div class="alert alert-warning py-2" style="font-size: 0.85rem;">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>Motif de rejet :</strong> <span id="rejectionReasonIndex"></span>
          </div>
          
          <div class="form-group mb-2">
            <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Titre du document *</label>
            <input type="text" class="form-control form-control-sm" name="titre" id="documentTitleResubmitIndex" required style="font-size: 0.85rem;">
          </div>
          
          <div class="form-group mb-2">
            <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Nouveau fichier (optionnel)</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" name="fichier" id="newFileIndex">
              <label class="custom-file-label" for="newFileIndex" style="font-size: 0.85rem;">Choisir un nouveau fichier</label>
            </div>
            <small class="form-text text-muted" style="font-size: 0.75rem;">Si aucun fichier n'est sélectionné, le fichier actuel sera conservé.</small>
          </div>
          
          <div class="form-group mb-2">
            <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Observations</label>
            <textarea class="form-control form-control-sm" name="observation" rows="2" placeholder="Ajouter des observations sur les corrections apportées..." style="font-size: 0.85rem;"></textarea>
          </div>
        </div>
        <div class="modal-footer py-2">
          <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal" style="font-size: 0.85rem;">Annuler</button>
          <button type="submit" class="btn btn-warning btn-sm" style="font-size: 0.85rem;">
            <i class="fas fa-redo mr-1"></i>Renvoyer pour validation
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

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

function archiveDocument(documentId, title) {
  const safeTitle = title.replace(/"/g, '&quot;').replace(/'/g, '&#39;');
  if (confirm('Êtes-vous sûr de vouloir archiver le document "' + safeTitle + '" ?')) {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '/documents/' + documentId + '/archive';
    
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
  const titleElement = document.getElementById('documentTitleRejectIndex');
  if (titleElement) {
    titleElement.textContent = title;
  }
  const formElement = document.getElementById('rejectFormIndex');
  if (formElement) {
    formElement.action = '/documents/' + documentId + '/reject';
  }
  
  // Wait for jQuery or use vanilla JS
  if (typeof jQuery !== 'undefined') {
    jQuery('#rejectModalIndex').modal('show');
  } else {
    const modal = document.getElementById('rejectModalIndex');
    if (modal) {
      modal.style.display = 'block';
      modal.classList.add('show');
      document.body.classList.add('modal-open');
    }
  }
}

function showResubmitModal(documentId, title, rejectionReason) {
  const titleElement = document.getElementById('documentTitleResubmitIndex');
  const reasonElement = document.getElementById('rejectionReasonIndex');
  const formElement = document.getElementById('resubmitFormIndex');
  
  if (titleElement) {
    titleElement.value = title;
  }
  if (reasonElement) {
    reasonElement.textContent = rejectionReason || '';
  }
  if (formElement) {
    formElement.action = '/documents/' + documentId + '/resubmit';
  }
  
  // Wait for jQuery or use vanilla JS
  if (typeof jQuery !== 'undefined') {
    jQuery('#resubmitModalIndex').modal('show');
  } else {
    const modal = document.getElementById('resubmitModalIndex');
    if (modal) {
      modal.style.display = 'block';
      modal.classList.add('show');
      document.body.classList.add('modal-open');
    }
  }
}

// File input label update
document.addEventListener('DOMContentLoaded', function() {
  const fileInput = document.getElementById('newFileIndex');
  if (fileInput) {
    fileInput.addEventListener('change', function(e) {
      const fileName = e.target.files[0] ? e.target.files[0].name : 'Choisir un fichier';
      const label = fileInput.nextElementSibling;
      if (label && label.classList.contains('custom-file-label')) {
        label.textContent = fileName;
      }
    });
  }
  
  // Close modal on backdrop click (vanilla JS fallback)
  const modals = document.querySelectorAll('.modal');
  modals.forEach(function(modal) {
    modal.addEventListener('click', function(e) {
      if (e.target === modal) {
        modal.style.display = 'none';
        modal.classList.remove('show');
        document.body.classList.remove('modal-open');
      }
    });
  });
  
  // Close modal on close button click
  const closeButtons = document.querySelectorAll('[data-dismiss="modal"]');
  closeButtons.forEach(function(btn) {
    btn.addEventListener('click', function() {
      const modal = this.closest('.modal');
      if (modal) {
        if (typeof jQuery !== 'undefined') {
          jQuery(modal).modal('hide');
        } else {
          modal.style.display = 'none';
          modal.classList.remove('show');
          document.body.classList.remove('modal-open');
        }
      }
    });
  });
});
</script>
@endsection
