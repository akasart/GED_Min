@extends('layouts.app')

@section('title', 'Créer un Agent')

@section('content')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Créer un Agent</h3>
    </div>
    <div class="card-body">
      <form action="{{ route('agents.store') }}" method="POST">
        @csrf
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="matricule">Matricule</label>
              <input type="text" class="form-control" id="matricule" name="matricule" readonly 
                     value="{{ 'MTF-' . now()->year . '-' . str_pad((App\Models\Agent::where('matricule', 'like', 'MTF-' . now()->year . '-%')->count() + 1), 3, '0', STR_PAD_LEFT) }}" 
                     style="background-color: #f8f9fa;">
              <small class="form-text text-muted">Généré automatiquement</small>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="nom">Nom *</label>
              <input type="text" class="form-control @error('nom') is-invalid @enderror" id="nom" name="nom" value="{{ old('nom') }}" required>
              @error('nom')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="prenom">Prénom</label>
              <input type="text" class="form-control @error('prenom') is-invalid @enderror" id="prenom" name="prenom" value="{{ old('prenom') }}">
              @error('prenom')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="direction">Direction</label>
              <select class="form-control @error('direction') is-invalid @enderror" id="direction" name="direction">
                <option value="">-- Sélectionner une direction --</option>
                @foreach(\App\Models\Direction::all() as $direction)
                  <option value="{{ $direction->libelle }}" {{ old('direction') == $direction->libelle ? 'selected' : '' }}>
                    {{ $direction->libelle }}
                  </option>
                @endforeach
              </select>
              @error('direction')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="service">Service</label>
              <select class="form-control @error('service') is-invalid @enderror" id="service" name="service">
                <option value="">-- Sélectionner un service --</option>
                @foreach(\App\Models\Service::all() as $service)
                  <option value="{{ $service->name }}" {{ old('service') == $service->name ? 'selected' : '' }}>
                    {{ $service->name }}
                  </option>
                @endforeach
              </select>
              @error('service')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="fonction">Fonction</label>
              <input type="text" class="form-control @error('fonction') is-invalid @enderror" id="fonction" name="fonction" value="{{ old('fonction') }}">
              @error('fonction')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="ministere_rattachement">Ministère de Rattachement</label>
              <select class="form-control @error('ministere_rattachement') is-invalid @enderror" id="ministere_rattachement" name="ministere_rattachement">
                <option value="">-- Sélectionner un ministère --</option>
                @foreach(\App\Models\Ministere::all() as $ministere)
                  <option value="{{ $ministere->libelle }}" {{ old('ministere_rattachement') == $ministere->libelle ? 'selected' : '' }}>
                    {{ $ministere->libelle }}
                  </option>
                @endforeach
              </select>
              @error('ministere_rattachement')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="date_entree">Date d'Entrée</label>
              <input type="date" class="form-control @error('date_entree') is-invalid @enderror" id="date_entree" name="date_entree" value="{{ old('date_entree') }}">
              @error('date_entree')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
        <hr>
        <h5 class="mb-3">Informations de Connexion (Tous les agents sont des utilisateurs)</h5>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="username">Nom d'utilisateur *</label>
              <input type="text" class="form-control @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" required>
              @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="email">Email</label>
              <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}">
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="password">Mot de passe *</label>
              <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" required>
              @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <small class="form-text text-muted">Minimum 6 caractères</small>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="role">Rôle</label>
              <select class="form-control @error('role') is-invalid @enderror" id="role" name="role">
                <option value="utilisateur" {{ old('role', 'utilisateur') == 'utilisateur' ? 'selected' : '' }}>Utilisateur</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrateur</option>
                <option value="rh" {{ old('role') == 'rh' ? 'selected' : '' }}>RH</option>
              </select>
              @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Créer</button>
          <a href="{{ route('agents.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
      </form>
    </div>
  </div>
@endsection
