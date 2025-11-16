@extends('layouts.app')

@section('title', 'Documents Rejetés')

@section('content')
<div class="container-fluid">
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-dark">
      <i class="fas fa-times-circle mr-2"></i>Documents Rejetés
    </h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary">Accueil</a></li>
        <li class="breadcrumb-item active text-dark">Documents Rejetés</li>
      </ol>
    </nav>
  </div>

  <!-- Info Card -->
  <div class="card mb-4">
    <div class="card-body bg-light">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h6 class="mb-1"><i class="fas fa-info-circle mr-2"></i>Documents Rejetés</h6>
          <p class="mb-0 text-muted">Cette section contient tous les documents rejetés. Vous pouvez les corriger et les renvoyer pour validation.</p>
        </div>
        <div class="col-md-4 text-right">
          <span class="badge badge-danger badge-lg">{{ $documents->total() }} document(s) rejeté(s)</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Documents Rejetés Table -->
  <div class="card">
    <div class="card-body p-0">
      @if($documents->isEmpty())
        <div class="text-center py-5">
          <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
          <p class="text-muted">Aucun document rejeté trouvé.</p>
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
                <th>Date de rejet</th>
                <th>Motif de rejet</th>
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
                    <span class="text-muted">{{ $doc->rejected_at ? $doc->rejected_at->format('d/m/Y H:i') : '-' }}</span>
                  </td>
                  <td>
                    <div class="text-danger">
                      <i class="fas fa-exclamation-triangle mr-1"></i>
                      {{ Str::limit($doc->motif_rejet, 50) }}
                    </div>
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
                      <button class="btn btn-sm btn-outline-warning" title="Corriger et renvoyer" onclick="showResubmitModal({{ $doc->id }}, {{ json_encode($doc->titre) }}, {{ json_encode($doc->motif_rejet ?? '') }})">
                        <i class="fas fa-redo"></i>
                      </button>
                      @if(auth()->user()->isAdmin())
                        <button class="btn btn-sm btn-outline-info" title="Historique" onclick="showHistory({{ $doc->id }})">
                          <i class="fas fa-history"></i>
                        </button>
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
    <div class="d-flex justify-content-between align-items-center mt-4">
      <div class="text-muted">
        Affichage de {{ $documents->firstItem() ?? 0 }}-{{ $documents->lastItem() ?? 0 }} sur {{ $documents->total() }} documents rejetés
      </div>
      <div>
        {{ $documents->links() }}
      </div>
    </div>
  @endif
</div>

<!-- Resubmit Modal -->
<div class="modal fade" id="resubmitModalRejected" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header py-2">
        <h6 class="modal-title" style="font-size: 0.9rem;">Corriger et Renvoyer le Document</h6>
        <button type="button" class="close" data-dismiss="modal" style="font-size: 1.2rem;">
          <span>&times;</span>
        </button>
      </div>
      <form id="resubmitFormRejected" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="modal-body py-3">
          <div class="alert alert-warning py-2" style="font-size: 0.85rem;">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            <strong>Motif de rejet :</strong> <span id="rejectionReasonRejected"></span>
          </div>
          
          <div class="form-group mb-2">
            <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Titre du document *</label>
            <input type="text" class="form-control form-control-sm" name="titre" id="documentTitleRejected" required style="font-size: 0.85rem;">
          </div>
          
          <div class="form-group mb-2">
            <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Nouveau fichier (optionnel)</label>
            <div class="custom-file">
              <input type="file" class="custom-file-input" name="fichier" id="newFileRejected">
              <label class="custom-file-label" for="newFileRejected" style="font-size: 0.85rem;">Choisir un nouveau fichier</label>
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

<!-- History Modal -->
<div class="modal fade" id="historyModal" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Historique du Document</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <div class="modal-body" id="historyContent">
        <!-- Content will be loaded via AJAX -->
      </div>
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
function showResubmitModal(documentId, title, rejectionReason) {
  const titleElement = document.getElementById('documentTitleRejected');
  const reasonElement = document.getElementById('rejectionReasonRejected');
  const formElement = document.getElementById('resubmitFormRejected');
  
  if (titleElement) {
    titleElement.value = title;
  }
  if (reasonElement) {
    reasonElement.textContent = rejectionReason || '';
  }
  if (formElement) {
    formElement.action = '/documents/' + documentId + '/resubmit';
  }
  
  if (typeof jQuery !== 'undefined') {
    jQuery('#resubmitModalRejected').modal('show');
  } else {
    const modal = document.getElementById('resubmitModalRejected');
    if (modal) {
      modal.style.display = 'block';
      modal.classList.add('show');
      document.body.classList.add('modal-open');
    }
  }
}

function showHistory(documentId) {
  // Redirect to history page
  window.location.href = '/documents/' + documentId + '/history';
}

// File input label update
document.addEventListener('DOMContentLoaded', function() {
  const fileInput = document.getElementById('newFileRejected');
  if (fileInput) {
    fileInput.addEventListener('change', function(e) {
      const fileName = e.target.files[0] ? e.target.files[0].name : 'Choisir un fichier';
      const label = fileInput.nextElementSibling;
      if (label && label.classList.contains('custom-file-label')) {
        label.textContent = fileName;
      }
    });
  }
});
</script>
@endsection
