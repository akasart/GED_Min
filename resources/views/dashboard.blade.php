@extends('layouts.app')

@section('title', 'Tableau de Bord')

@section('content')
<div class="container-fluid" style="font-size: 0.875rem;">
  <!-- Summary Cards Section -->
  <div class="row mb-3">
    <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
      <div class="card bg-gradient-info text-white h-100">
        <div class="card-body d-flex align-items-center py-2">
          <div class="flex-grow-1">
            <div style="font-size: 1.8rem; font-weight: bold;">{{ $counts['documents'] ?? 0 }}</div>
            <div style="font-size: 0.85rem;">Mes Documents</div>
          </div>
          <div class="ml-2">
            <i class="fas fa-folder-open" style="font-size: 2rem; opacity: 0.75;"></i>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
      <div class="card bg-gradient-warning text-white h-100">
        <div class="card-body d-flex align-items-center py-2">
          <div class="flex-grow-1">
            <div style="font-size: 1.8rem; font-weight: bold;">{{ $counts['pending'] ?? 0 }}</div>
            <div style="font-size: 0.85rem;">En Attente</div>
          </div>
          <div class="ml-2">
            <i class="fas fa-clock" style="font-size: 2rem; opacity: 0.75;"></i>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
      <div class="card bg-gradient-success text-white h-100">
        <div class="card-body d-flex align-items-center py-2">
          <div class="flex-grow-1">
            <div style="font-size: 1.8rem; font-weight: bold;">{{ $counts['validated'] ?? 0 }}</div>
            <div style="font-size: 0.85rem;">Validés</div>
          </div>
          <div class="ml-2">
            <i class="fas fa-check-circle" style="font-size: 2rem; opacity: 0.75;"></i>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
      <div class="card bg-gradient-danger text-white h-100">
        <div class="card-body d-flex align-items-center py-2">
          <div class="flex-grow-1">
            <div style="font-size: 1.8rem; font-weight: bold;">{{ $counts['rejected'] ?? 0 }}</div>
            <div style="font-size: 0.85rem;">Rejetés</div>
          </div>
          <div class="ml-2">
            <i class="fas fa-times-circle" style="font-size: 2rem; opacity: 0.75;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>

  @if($user->isAdmin())
  <!-- Admin Additional Stats -->
  <div class="row mb-3">
    <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
      <div class="card bg-gradient-primary text-white h-100">
        <div class="card-body d-flex align-items-center py-2">
          <div class="flex-grow-1">
            <div style="font-size: 1.8rem; font-weight: bold;">{{ $counts['users'] ?? 0 }}</div>
            <div style="font-size: 0.85rem;">Utilisateurs</div>
          </div>
          <div class="ml-2">
            <i class="fas fa-users" style="font-size: 2rem; opacity: 0.75;"></i>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
      <div class="card bg-gradient-secondary text-white h-100">
        <div class="card-body d-flex align-items-center py-2">
          <div class="flex-grow-1">
            <div style="font-size: 1.8rem; font-weight: bold;">{{ $counts['archived'] ?? 0 }}</div>
            <div style="font-size: 0.85rem;">Archivés</div>
          </div>
          <div class="ml-2">
            <i class="fas fa-archive" style="font-size: 2rem; opacity: 0.75;"></i>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
      <div class="card bg-gradient-info text-white h-100">
        <div class="card-body d-flex align-items-center py-2">
          <div class="flex-grow-1">
            <div style="font-size: 1.8rem; font-weight: bold;">{{ $counts['monthly_documents'] ?? 0 }}</div>
            <div style="font-size: 0.85rem;">Ce Mois</div>
          </div>
          <div class="ml-2">
            <i class="fas fa-calendar-alt" style="font-size: 2rem; opacity: 0.75;"></i>
          </div>
        </div>
      </div>
    </div>
    
    <div class="col-lg-3 col-md-6 col-sm-6 mb-2">
      <div class="card bg-gradient-success text-white h-100">
        <div class="card-body d-flex align-items-center py-2">
          <div class="flex-grow-1">
            <div style="font-size: 1.8rem; font-weight: bold;">{{ $counts['agents'] ?? 0 }}</div>
            <div style="font-size: 0.85rem;">Agents</div>
          </div>
          <div class="ml-2">
            <i class="fas fa-user-tie" style="font-size: 2rem; opacity: 0.75;"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endif

  <!-- Recent Activities Section -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header bg-primary text-white d-flex align-items-center py-2">
          <i class="fas fa-history mr-2" style="font-size: 0.9rem;"></i>
          <h6 class="mb-0" style="font-size: 0.9rem;">Dernières actions</h6>
        </div>
        <div class="card-body p-0">
          @if($recentActivities->isEmpty())
            <div class="text-center py-3">
              <i class="fas fa-history fa-2x text-muted mb-2" style="font-size: 1.5rem;"></i>
              <p class="text-muted mb-0" style="font-size: 0.85rem;">Aucune activité récente</p>
            </div>
          @else
            <div class="list-group list-group-flush">
              @foreach($recentActivities as $activity)
                <div class="list-group-item d-flex align-items-center py-2" style="font-size: 0.85rem;">
                  <div class="mr-2">
                    @if($activity->action == 'created')
                      <i class="fas fa-plus text-warning" style="font-size: 0.9rem;"></i>
                    @elseif($activity->action == 'validated')
                      <i class="fas fa-check text-success" style="font-size: 0.9rem;"></i>
                    @elseif($activity->action == 'rejected')
                      <i class="fas fa-times text-danger" style="font-size: 0.9rem;"></i>
                    @elseif($activity->action == 'updated')
                      <i class="fas fa-edit text-info" style="font-size: 0.9rem;"></i>
                    @elseif($activity->action == 'downloaded')
                      <i class="fas fa-download text-primary" style="font-size: 0.9rem;"></i>
                    @elseif($activity->action == 'deleted')
                      <i class="fas fa-trash text-danger" style="font-size: 0.9rem;"></i>
                    @else
                      <i class="fas fa-circle text-secondary" style="font-size: 0.9rem;"></i>
                    @endif
                  </div>
                  <div class="flex-grow-1">
                    <div style="font-size: 0.85rem;">
                      <strong>
                        @if($activity->action == 'created')
                          Ajout
                        @elseif($activity->action == 'validated')
                          Validation
                        @elseif($activity->action == 'rejected')
                          Rejet
                        @elseif($activity->action == 'updated')
                          Modification
                        @elseif($activity->action == 'downloaded')
                          Téléchargement
                        @elseif($activity->action == 'deleted')
                          Suppression
                        @else
                          Action
                        @endif
                      </strong>
                      <span class="ml-1">{{ $activity->document->titre ?? 'Document supprimé' }}</span>
                    </div>
                    @if($activity->document && $activity->document->type)
                      <small class="text-muted" style="font-size: 0.75rem;">{{ $activity->document->type->libelle }}</small>
                    @endif
                  </div>
                  <div class="text-muted" style="font-size: 0.75rem;">
                    {{ $activity->created_at->format('d/m/Y H:i') }}
                  </div>
                </div>
              @endforeach
            </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.bg-gradient-info {
  background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}
.bg-gradient-success {
  background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}
.bg-gradient-warning {
  background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
}
.bg-gradient-danger {
  background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}
.bg-gradient-primary {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}
.bg-gradient-secondary {
  background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
}
.list-group-item {
  border-left: none;
  border-right: none;
  padding: 0.5rem 1rem;
}
.list-group-item:first-child {
  border-top: none;
}
.list-group-item:last-child {
  border-bottom: none;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .card-body {
    padding: 0.75rem !important;
  }
}
</style>
@endsection
