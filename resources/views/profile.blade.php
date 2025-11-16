@extends('layouts.app')

@section('title', 'Mon Profil')

@section('content')
<div class="container-fluid">
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0 text-dark">Mon Profil</h1>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}" class="text-primary">Accueil</a></li>
        <li class="breadcrumb-item active text-dark">Mon Profil</li>
      </ol>
    </nav>
  </div>

  <div class="row">
    <!-- Profile Information -->
    <div class="col-lg-8">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Informations du Profil</h5>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="font-weight-bold">Nom d'utilisateur</label>
                <p class="form-control-plaintext">{{ $user->name }}</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="font-weight-bold">Email</label>
                <p class="form-control-plaintext">{{ $user->email }}</p>
              </div>
            </div>
          </div>
          
          @if($agent)
            <hr>
            <h6 class="text-primary mb-3">Informations Agent</h6>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bold">Matricule</label>
                  <p class="form-control-plaintext">{{ $agent->matricule }}</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bold">Nom complet</label>
                  <p class="form-control-plaintext">{{ $agent->nom }} {{ $agent->prenom }}</p>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bold">Direction</label>
                  <p class="form-control-plaintext">{{ $agent->direction ?? 'Non définie' }}</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bold">Service</label>
                  <p class="form-control-plaintext">{{ $agent->service ?? 'Non défini' }}</p>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bold">Fonction</label>
                  <p class="form-control-plaintext">{{ $agent->fonction ?? 'Non définie' }}</p>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bold">Ministère de Rattachement</label>
                  <p class="form-control-plaintext">{{ $agent->ministere_rattachement ?? 'Non défini' }}</p>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label class="font-weight-bold">Date d'entrée</label>
                  <p class="form-control-plaintext">{{ $agent->date_entree ? $agent->date_entree->format('d/m/Y') : 'Non définie' }}</p>
                </div>
              </div>
            </div>
          @else
            <div class="alert alert-info">
              <i class="fas fa-info-circle mr-2"></i>
              Aucune information d'agent associée à ce compte.
            </div>
          @endif
        </div>
      </div>
    </div>

    <!-- Profile Actions -->
    <div class="col-lg-4">
      <div class="card">
        <div class="card-header">
          <h5 class="card-title mb-0">Actions</h5>
        </div>
        <div class="card-body">
          <div class="d-grid gap-2">
            <a href="{{ route('settings') }}" class="btn btn-primary">
              <i class="fas fa-cogs mr-2"></i>Paramètres
            </a>
            <button class="btn btn-warning" onclick="changePassword()">
              <i class="fas fa-key mr-2"></i>Changer le mot de passe
            </button>
            @if(!$agent)
              <a href="{{ route('agents.create') }}" class="btn btn-info">
                <i class="fas fa-user-plus mr-2"></i>Créer un profil agent
              </a>
            @endif
          </div>
        </div>
      </div>

      <!-- Account Statistics -->
      <div class="card mt-3">
        <div class="card-header">
          <h5 class="card-title mb-0">Statistiques</h5>
        </div>
        <div class="card-body">
          <div class="row text-center">
            <div class="col-6">
              <div class="border-right">
                <h4 class="text-primary mb-0">{{ \App\Models\Document::where('created_by', $user->id)->count() }}</h4>
                <small class="text-muted">Documents créés</small>
              </div>
            </div>
            <div class="col-6">
              <h4 class="text-success mb-0">{{ \App\Models\DocumentHistory::where('user_id', $user->id)->count() }}</h4>
              <small class="text-muted">Actions effectuées</small>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
function changePassword() {
  // Simple password change modal
  const newPassword = prompt('Nouveau mot de passe:');
  if (newPassword && newPassword.length >= 6) {
    // Here you would typically make an AJAX request to update the password
    alert('Fonctionnalité de changement de mot de passe à implémenter');
  } else if (newPassword) {
    alert('Le mot de passe doit contenir au moins 6 caractères');
  }
}
</script>
@endsection

