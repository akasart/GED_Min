@extends('layouts.app')

@section('title', 'Historique des Documents')

@section('content')
<div class="container-fluid">
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-dark">Historique des Documents</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary">Accueil</a></li>
        <li class="breadcrumb-item active text-dark">Historique</li>
      </ol>
    </nav>
  </div>

  <!-- Filters Section -->
  <div class="card mb-4">
    <div class="card-body">
      <div class="row align-items-center">
        <div class="col-md-3">
          <div class="form-group mb-0">
            <label class="small">Période</label>
            <select class="form-control form-control-sm" id="periodFilter">
              <option value="all">Toutes les périodes</option>
              <option value="today">Aujourd'hui</option>
              <option value="week">Cette semaine</option>
              <option value="month">Ce mois</option>
              <option value="year">Cette année</option>
            </select>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group mb-0">
            <label class="small">Type d'action</label>
            <select class="form-control form-control-sm" id="actionFilter">
              <option value="all">Toutes les actions</option>
              <option value="created">Création</option>
              <option value="updated">Modification</option>
              <option value="downloaded">Téléchargement</option>
              <option value="deleted">Suppression</option>
            </select>
          </div>
        </div>
        @if(auth()->user()->isAdmin())
        <div class="col-md-3">
          <div class="form-group mb-0">
            <label class="small">Agent</label>
            <select class="form-control form-control-sm" id="agentFilter">
              <option value="all">Tous les agents</option>
              @foreach(\App\Models\Agent::all() as $agent)
                <option value="{{ $agent->id }}">{{ $agent->nom }} {{ $agent->prenom }}</option>
              @endforeach
            </select>
          </div>
        </div>
        @endif
        <div class="col-md-3 text-right">
          <button class="btn btn-primary btn-sm" onclick="applyFilters()">
            <i class="fas fa-filter mr-1"></i>Appliquer
          </button>
          <button class="btn btn-secondary btn-sm" onclick="resetFilters()">
            <i class="fas fa-undo mr-1"></i>Réinitialiser
          </button>
        </div>
      </div>
    </div>
  </div>

  <!-- History Timeline -->
  <div class="card">
    <div class="card-header">
      <h5 class="card-title mb-0">Chronologie des Actions</h5>
    </div>
    <div class="card-body">
      @if($history->isEmpty())
        <div class="text-center py-5">
          <i class="fas fa-history fa-3x text-muted mb-3"></i>
          <p class="text-muted">Aucun historique trouvé.</p>
        </div>
      @else
        <div class="timeline">
          @foreach($history as $item)
            <div class="timeline-item">
              <div class="timeline-marker">
                @if($item->action == 'created')
                  <i class="fas fa-plus-circle text-success"></i>
                @elseif($item->action == 'updated')
                  <i class="fas fa-edit text-warning"></i>
                @elseif($item->action == 'downloaded')
                  <i class="fas fa-download text-info"></i>
                @elseif($item->action == 'deleted')
                  <i class="fas fa-trash text-danger"></i>
                @else
                  <i class="fas fa-circle text-secondary"></i>
                @endif
              </div>
              <div class="timeline-content">
                <div class="timeline-header">
                  <h6 class="mb-1">
                    @if($item->action == 'created')
                      Document créé
                    @elseif($item->action == 'updated')
                      Document modifié
                    @elseif($item->action == 'downloaded')
                      Document téléchargé
                    @elseif($item->action == 'deleted')
                      Document supprimé
                    @else
                      Action effectuée
                    @endif
                  </h6>
                  <small class="text-muted">{{ $item->created_at->diffForHumans() }}</small>
                </div>
                <div class="timeline-body">
                  <p class="mb-1">
                    <strong>{{ $item->document->titre ?? 'Document supprimé' }}</strong>
                    @if($item->document)
                      <span class="text-muted">({{ $item->document->type->libelle ?? 'Type inconnu' }})</span>
                    @endif
                  </p>
                  <p class="mb-0 small text-muted">
                    Par: {{ $item->user->username ?? 'Utilisateur inconnu' }}
                    @if($item->agent)
                      | Agent: {{ $item->agent->nom }} {{ $item->agent->prenom }}
                    @endif
                  </p>
                  @if($item->description)
                    <p class="mb-0 small">{{ $item->description }}</p>
                  @endif
                </div>
              </div>
            </div>
          @endforeach
        </div>
        
        <!-- Pagination -->
        @if($history->hasPages())
          <div class="d-flex justify-content-center mt-4">
            {{ $history->links() }}
          </div>
        @endif
      @endif
    </div>
  </div>
</div>

<style>
.timeline {
  position: relative;
  padding-left: 30px;
}

.timeline-item {
  position: relative;
  margin-bottom: 30px;
  padding-left: 40px;
}

.timeline-item:before {
  content: '';
  position: absolute;
  left: 15px;
  top: 0;
  bottom: -30px;
  width: 2px;
  background-color: #dee2e6;
}

.timeline-item:last-child:before {
  bottom: 0;
}

.timeline-marker {
  position: absolute;
  left: 0;
  top: 5px;
  width: 30px;
  height: 30px;
  background-color: #fff;
  border: 2px solid #dee2e6;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 1;
}

.timeline-content {
  background-color: #f8f9fa;
  border-radius: 8px;
  padding: 15px;
  border-left: 4px solid #007bff;
}

.timeline-header {
  display: flex;
  justify-content: between;
  align-items: center;
  margin-bottom: 10px;
}

.timeline-body p {
  margin-bottom: 5px;
}
</style>

<script>
function applyFilters() {
  const period = document.getElementById('periodFilter').value;
  const action = document.getElementById('actionFilter').value;
  const agent = document.getElementById('agentFilter').value;
  
  // Build URL with filters
  const url = new URL(window.location);
  url.searchParams.set('period', period);
  url.searchParams.set('action', action);
  url.searchParams.set('agent', agent);
  
  window.location.href = url.toString();
}

function resetFilters() {
  document.getElementById('periodFilter').value = 'all';
  document.getElementById('actionFilter').value = 'all';
  document.getElementById('agentFilter').value = 'all';
  
  // Remove filter parameters from URL
  const url = new URL(window.location);
  url.searchParams.delete('period');
  url.searchParams.delete('action');
  url.searchParams.delete('agent');
  
  window.location.href = url.toString();
}
</script>
@endsection
