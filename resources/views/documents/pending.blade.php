@extends('layouts.app')

@section('title', 'Documents en Attente')

@section('content')
<div class="container-fluid">
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-dark">
      <i class="fas fa-clock mr-2"></i>Documents en Attente de Validation
    </h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary">Accueil</a></li>
        <li class="breadcrumb-item active text-dark">Documents en Attente</li>
      </ol>
    </nav>
  </div>

  <!-- Info Card -->
  <div class="card mb-4">
    <div class="card-body bg-light">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h6 class="mb-1"><i class="fas fa-info-circle mr-2"></i>Validation des Documents</h6>
          <p class="mb-0 text-muted">Cette section contient tous les documents en attente de validation. En tant qu'administrateur, vous pouvez les valider ou les rejeter.</p>
        </div>
        <div class="col-md-4 text-right">
          <span class="badge badge-warning badge-lg">{{ $documents->total() }} document(s) en attente</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Documents en Attente Table -->
  <div class="card">
    <div class="card-body p-0">
      @if($documents->isEmpty())
        <div class="text-center py-5">
          <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
          <p class="text-muted">Aucun document en attente de validation.</p>
        </div>
      @else
        <div class="table-responsive">
          <table class="table table-hover mb-0">
            <thead class="thead-light">
              <tr>
                <th>Référence</th>
                <th>Titre</th>
                <th>Type</th>
                <th>Agent</th>
                <th>Date de création</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($documents as $doc)
                <tr>
                  <td>
                    <span class="font-weight-bold text-primary">DOC-{{ now()->year }}-{{ str_pad($doc->id, 3, '0', STR_PAD_LEFT) }}</span>
                  </td>
                  <td>
                    <div class="font-weight-bold">{{ $doc->titre }}</div>
                    @if($doc->observation)
                      <small class="text-muted">{{ Str::limit($doc->observation, 50) }}</small>
                    @endif
                  </td>
                  <td>
                    <div class="d-flex align-items-center">
                      @if($doc->type && $doc->type->libelle == 'PDF')
                        <i class="fas fa-file-pdf text-danger mr-2"></i>
                      @elseif($doc->type && $doc->type->libelle == 'WORD')
                        <i class="fas fa-file-word text-primary mr-2"></i>
                      @elseif($doc->type && $doc->type->libelle == 'EXCEL')
                        <i class="fas fa-file-excel text-success mr-2"></i>
                      @else
                        <i class="fas fa-file text-secondary mr-2"></i>
                      @endif
                      <span>{{ $doc->type ? $doc->type->libelle : '-' }}</span>
                    </div>
                  </td>
                  <td>
                    <div>
                      <div class="font-weight-bold">{{ $doc->agent ? $doc->agent->nom . ' ' . $doc->agent->prenom : '-' }}</div>
                      <small class="text-muted">{{ $doc->agent ? $doc->agent->matricule : '-' }}</small>
                    </div>
                  </td>
                  <td>
                    <span class="text-muted">{{ $doc->created_at->format('d/m/Y H:i') }}</span>
                  </td>
                  <td>
                    <div class="btn-group" role="group">
                      <a href="{{ route('documents.show', $doc) }}" class="btn btn-sm btn-outline-primary" title="Voir">
                        <i class="fas fa-eye"></i>
                      </a>
                      @if($doc->fichier)
                        <a href="{{ asset('storage/' . $doc->fichier) }}" class="btn btn-sm btn-outline-success" title="Télécharger" target="_blank">
                          <i class="fas fa-download"></i>
                        </a>
                      @endif
                      <button class="btn btn-sm btn-success" title="Valider" onclick="validateDocument({{ $doc->id }}, {{ json_encode($doc->titre) }})">
                        <i class="fas fa-check"></i>
                      </button>
                      <button class="btn btn-sm btn-danger" title="Rejeter" onclick="showRejectModal({{ $doc->id }}, {{ json_encode($doc->titre) }})">
                        <i class="fas fa-times"></i>
                      </button>
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
    <div class="d-flex justify-content-between align-items-center mt-4">
      <div class="text-muted">
        Affichage de {{ $documents->firstItem() ?? 0 }}-{{ $documents->lastItem() ?? 0 }} sur {{ $documents->total() }} documents en attente
      </div>
      <div>
        {{ $documents->links() }}
      </div>
    </div>
  @endif
</div>

<!-- Reject Modal -->
<div class="modal fade" id="rejectModalPending" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header py-2">
        <h6 class="modal-title" style="font-size: 0.9rem;">Rejeter le Document</h6>
        <button type="button" class="close" data-dismiss="modal" style="font-size: 1.2rem;">
          <span>&times;</span>
        </button>
      </div>
      <form id="rejectFormPending" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body py-3">
          <div class="alert alert-warning py-2" style="font-size: 0.85rem;">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Vous êtes sur le point de rejeter le document : <strong id="documentTitlePending"></strong>
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
.table th {
  border-top: none;
  font-weight: 600;
  color: #495057;
  background-color: #f8f9fa;
}
.table td {
  vertical-align: middle;
  border-top: 1px solid #dee2e6;
}
.badge-lg {
  font-size: 0.9rem;
  padding: 0.5rem 1rem;
}
.btn-group .btn {
  margin-right: 2px;
}
.btn-group .btn:last-child {
  margin-right: 0;
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
  const titleElement = document.getElementById('documentTitlePending');
  if (titleElement) {
    titleElement.textContent = title;
  }
  const formElement = document.getElementById('rejectFormPending');
  if (formElement) {
    formElement.action = '/documents/' + documentId + '/reject';
  }
  
  if (typeof jQuery !== 'undefined') {
    jQuery('#rejectModalPending').modal('show');
  } else {
    const modal = document.getElementById('rejectModalPending');
    if (modal) {
      modal.style.display = 'block';
      modal.classList.add('show');
      document.body.classList.add('modal-open');
    }
  }
}
</script>
@endsection
