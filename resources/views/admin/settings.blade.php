@extends('layouts.app')

@section('title', 'Paramètres Administrateur')

@section('content')
<div class="container-fluid">
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-dark">
      <i class="fas fa-cogs mr-2"></i>Paramètres Administrateur
    </h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary">Accueil</a></li>
        <li class="breadcrumb-item active text-dark">Paramètres Admin</li>
      </ol>
    </nav>
  </div>

  <!-- Stats Cards -->
  <div class="row mb-4">
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
      <div class="card bg-gradient-primary text-white h-100">
        <div class="card-body text-center">
          <div class="display-4 font-weight-bold">{{ $stats['users'] }}</div>
          <div class="h6 mb-0">Utilisateurs</div>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
      <div class="card bg-gradient-success text-white h-100">
        <div class="card-body text-center">
          <div class="display-4 font-weight-bold">{{ $stats['agents'] }}</div>
          <div class="h6 mb-0">Agents</div>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
      <div class="card bg-gradient-info text-white h-100">
        <div class="card-body text-center">
          <div class="display-4 font-weight-bold">{{ $stats['document_types'] }}</div>
          <div class="h6 mb-0">Types</div>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
      <div class="card bg-gradient-warning text-white h-100">
        <div class="card-body text-center">
          <div class="display-4 font-weight-bold">{{ $stats['directions'] }}</div>
          <div class="h6 mb-0">Directions</div>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
      <div class="card bg-gradient-secondary text-white h-100">
        <div class="card-body text-center">
          <div class="display-4 font-weight-bold">{{ $stats['services'] }}</div>
          <div class="h6 mb-0">Services</div>
        </div>
      </div>
    </div>
    <div class="col-lg-2 col-md-4 col-sm-6 mb-3">
      <div class="card bg-gradient-danger text-white h-100">
        <div class="card-body text-center">
          <div class="display-4 font-weight-bold">{{ $stats['confidentialities'] }}</div>
          <div class="h6 mb-0">Confidentialités</div>
        </div>
      </div>
    </div>
  </div>

  <!-- Management Sections -->
  <div class="row">
    <!-- Users Management -->
    <div class="col-lg-6 mb-4">
      <div class="card">
        <div class="card-header bg-primary text-white">
          <h5 class="card-title mb-0">
            <i class="fas fa-users mr-2"></i>Gestion des Utilisateurs
          </h5>
        </div>
        <div class="card-body">
          <p class="text-muted mb-3">Gérer les utilisateurs du système et leurs rôles.</p>
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="h4 mb-0">{{ $stats['users'] }}</div>
              <small class="text-muted">Utilisateurs actifs</small>
            </div>
            <a href="{{ route('admin.users') }}" class="btn btn-primary">
              <i class="fas fa-cog mr-1"></i>Gérer
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Document Types Management -->
    <div class="col-lg-6 mb-4">
      <div class="card">
        <div class="card-header bg-success text-white">
          <h5 class="card-title mb-0">
            <i class="fas fa-file-alt mr-2"></i>Types de Documents
          </h5>
        </div>
        <div class="card-body">
          <p class="text-muted mb-3">Configurer les types de documents disponibles.</p>
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="h4 mb-0">{{ $stats['document_types'] }}</div>
              <small class="text-muted">Types configurés</small>
            </div>
            <a href="{{ route('admin.document-types') }}" class="btn btn-success">
              <i class="fas fa-cog mr-1"></i>Gérer
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- Security Settings -->
    <div class="col-lg-6 mb-4">
      <div class="card">
        <div class="card-header bg-warning text-white">
          <h5 class="card-title mb-0">
            <i class="fas fa-shield-alt mr-2"></i>Paramètres de Sécurité
          </h5>
        </div>
        <div class="card-body">
          <p class="text-muted mb-3">Configurer les paramètres de sécurité du système.</p>
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="h4 mb-0">Sécurité</div>
              <small class="text-muted">Configuration</small>
            </div>
            <a href="{{ route('admin.security') }}" class="btn btn-warning">
              <i class="fas fa-cog mr-1"></i>Configurer
            </a>
          </div>
        </div>
      </div>
    </div>

    <!-- System Info -->
    <div class="col-lg-6 mb-4">
      <div class="card">
        <div class="card-header bg-info text-white">
          <h5 class="card-title mb-0">
            <i class="fas fa-info-circle mr-2"></i>Informations Système
          </h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-6">
              <div class="text-center">
                <div class="h4 mb-0">{{ $stats['directions'] }}</div>
                <small class="text-muted">Directions</small>
              </div>
            </div>
            <div class="col-6">
              <div class="text-center">
                <div class="h4 mb-0">{{ $stats['services'] }}</div>
                <small class="text-muted">Services</small>
              </div>
            </div>
          </div>
          <hr>
          <div class="text-center">
            <small class="text-muted">
              <i class="fas fa-server mr-1"></i>
              Version: 1.0.0 | 
              <i class="fas fa-calendar mr-1"></i>
              {{ date('Y') }}
            </small>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Quick Actions -->
  <div class="row">
    <div class="col-12">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">
            <i class="fas fa-bolt mr-2"></i>Actions Rapides
          </h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-3 mb-2">
              <a href="{{ route('agents.create') }}" class="btn btn-outline-primary btn-block">
                <i class="fas fa-user-plus mr-1"></i>Nouvel Agent
              </a>
            </div>
            <div class="col-md-3 mb-2">
              <a href="{{ route('document-types.create') }}" class="btn btn-outline-success btn-block">
                <i class="fas fa-file-plus mr-1"></i>Nouveau Type
              </a>
            </div>
            <div class="col-md-3 mb-2">
              <a href="{{ route('directions.create') }}" class="btn btn-outline-info btn-block">
                <i class="fas fa-building mr-1"></i>Nouvelle Direction
              </a>
            </div>
            <div class="col-md-3 mb-2">
              <a href="{{ route('services.create') }}" class="btn btn-outline-warning btn-block">
                <i class="fas fa-cogs mr-1"></i>Nouveau Service
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<style>
.bg-gradient-primary {
  background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
}
.bg-gradient-success {
  background: linear-gradient(135deg, #28a745 0%, #1e7e34 100%);
}
.bg-gradient-info {
  background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
}
.bg-gradient-warning {
  background: linear-gradient(135deg, #ffc107 0%, #e0a800 100%);
}
.bg-gradient-secondary {
  background: linear-gradient(135deg, #6c757d 0%, #545b62 100%);
}
.bg-gradient-danger {
  background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
}
.display-4 {
  font-size: 2rem;
  font-weight: 700;
}
</style>
@endsection
