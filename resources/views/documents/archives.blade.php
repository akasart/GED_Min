@extends('layouts.app')

@section('title', 'Archives des Documents')

@section('content')
<div class="container-fluid">
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-dark">
      <i class="fas fa-archive mr-2"></i>Archives des Documents
    </h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary">Accueil</a></li>
        <li class="breadcrumb-item active text-dark">Archives</li>
      </ol>
    </nav>
  </div>

  <!-- Info Card -->
  <div class="card mb-4">
    <div class="card-body bg-light">
      <div class="row align-items-center">
        <div class="col-md-8">
          <h6 class="mb-1"><i class="fas fa-info-circle mr-2"></i>Documents Archivés</h6>
          <p class="mb-0 text-muted">Cette section contient tous les documents validés. Les documents archivés sont en lecture seule.</p>
        </div>
        <div class="col-md-4 text-right">
          <span class="badge badge-success badge-lg">{{ $documents->total() }} document(s) archivé(s)</span>
        </div>
      </div>
    </div>
  </div>

  <!-- Search and Filters -->
  <div class="card mb-4">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-md-6">
          <form action="{{ route('documents.archives') }}" method="GET">
            <div class="input-group">
              <input type="text" class="form-control" name="q" placeholder="Rechercher dans les archives..." value="{{ request('q') }}">
              <div class="input-group-append">
                <button class="btn btn-primary" type="submit">
                  <i class="fas fa-search"></i>
                </button>
              </div>
            </div>
          </form>
        </div>
        <div class="col-md-6 text-right">
          <button class="btn btn-info mr-2">
            <i class="fas fa-filter mr-1"></i>Filtrer
          </button>
          <button class="btn btn-success">
            <i class="fas fa-download mr-1"></i>Exporter
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- Archives Table -->
  <div class="card">
    <div class="card-body p-0">
      @if($documents->isEmpty())
        <div class="text-center py-5">
          <i class="fas fa-archive fa-3x text-muted mb-3"></i>
          <p class="text-muted">Aucun document archivé trouvé.</p>
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
                <th>Date de validation</th>
                <th>Validé par</th>
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
                    <span class="text-muted">{{ $doc->validated_at ? $doc->validated_at->format('d/m/Y H:i') : '-' }}</span>
                  </td>
                  <td>
                    <span class="text-muted">{{ $doc->validator ? $doc->validator->name : '-' }}</span>
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
        Affichage de {{ $documents->firstItem() ?? 0 }}-{{ $documents->lastItem() ?? 0 }} sur {{ $documents->total() }} documents archivés
      </div>
      <div>
        {{ $documents->links() }}
      </div>
    </div>
  @endif
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
function showHistory(documentId) {
  // Load document history via AJAX
  fetch(`/documents/${documentId}/history`)
    .then(response => response.text())
    .then(html => {
      document.getElementById('historyContent').innerHTML = html;
      $('#historyModal').modal('show');
    })
    .catch(error => {
      console.error('Error loading history:', error);
      alert('Erreur lors du chargement de l\'historique');
    });
}
</script>
@endsection
