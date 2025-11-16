@extends('layouts.app')

@section('title', 'Éditer le document')

@section('content')
  <div class="container-fluid" style="font-size: 0.875rem;">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 style="font-size: 1.1rem; margin: 0;">Formulaire édition document</h5>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0" style="font-size: 0.8rem;">
          <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
          <li class="breadcrumb-item"><a href="{{ route('documents.index') }}">Documents</a></li>
          <li class="breadcrumb-item active">Éditer Document</li>
        </ol>
      </nav>
    </div>

    <div class="card shadow-sm">
      <div class="card-header bg-primary text-white py-2">
        <h6 class="card-title mb-0" style="font-size: 0.9rem;"><i class="fas fa-file-alt mr-2"></i>Formulaire de Modification</h6>
      </div>
      <div class="card-body">
        <!-- Display Validation Errors -->
        @if($errors->any())
          <div class="alert alert-danger">
            <ul style="margin-bottom: 0; font-size: 0.85rem;">
              @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('documents.update', $document) }}" method="POST" enctype="multipart/form-data">
          @csrf @method('PUT')
          <input type="hidden" name="agent_id" value="{{ $document->agent_id }}">

          <!-- Informations de l'opérateur -->
          <div class="card mb-3">
            <div class="card-header bg-light py-2">
              <h6 class="mb-0" style="font-size: 0.9rem;"><i class="fas fa-user mr-2"></i>Opérateur (Lecture seule)</h6>
            </div>
            <div class="card-body py-3">
              <div class="row">
                <div class="col-md-6 col-lg-3 mb-2">
                  <div class="form-group mb-2">
                    <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Nom de l'opérateur</label>
                    <input type="text" class="form-control form-control-sm" readonly value="{{ optional(optional($document->agent)->user)->name ?? 'N/A' }}" style="font-size: 0.85rem; background-color: #e9ecef;">
                  </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-2">
                  <div class="form-group mb-2">
                    <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Prénom de l'opérateur</label>
                    <input type="text" class="form-control form-control-sm" readonly value="{{ optional($document->agent)->prenom ?? '' }}" style="font-size: 0.85rem; background-color: #e9ecef;">
                  </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-2">
                  <div class="form-group mb-2">
                    <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Matricule</label>
                    <input type="text" class="form-control form-control-sm" readonly value="{{ optional($document->agent)->matricule ?? 'N/A' }}" style="font-size: 0.85rem; background-color: #e9ecef;">
                  </div>
                </div>
                <div class="col-md-6 col-lg-3 mb-2">
                  <div class="form-group mb-2">
                    <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Ministère</label>
                    <input type="text" class="form-control form-control-sm" readonly value="{{ optional($document->agent)->ministere_rattachement ?? 'N/A' }}" style="font-size: 0.85rem; background-color: #e9ecef;">
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Informations du document -->
          <div class="card mb-3">
            <div class="card-header bg-light py-2">
              <h6 class="mb-0" style="font-size: 0.9rem;"><i class="fas fa-file-alt mr-2"></i>Informations du Document</h6>
            </div>
            <div class="card-body py-3">
              <div class="row">
                <div class="col-md-12 mb-2">
                  <div class="form-group mb-2">
                    <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Titre *</label>
                    <input type="text" name="titre" class="form-control form-control-sm" value="{{ old('titre', $document->titre) }}" required style="font-size: 0.85rem;">
                    @error('titre')<small class="text-danger" style="font-size: 0.75rem;">{{ $message }}</small>@enderror
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-6 mb-2">
                  <div class="form-group mb-2">
                    <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Fichier (laisser vide pour conserver)</label>
                    <input type="file" name="fichier" class="form-control form-control-sm" style="font-size: 0.85rem;">
                    @error('fichier')<small class="text-danger" style="font-size: 0.75rem;">{{ $message }}</small>@enderror
                    <small class="text-muted" style="font-size: 0.75rem;">Fichier actuel: {{ $document->fichier }}</small>
                  </div>
                </div>
                <div class="col-md-6 mb-2">
                  <div class="form-group mb-2">
                    <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">État *</label>
                    <select name="etat" class="form-control form-control-sm" required style="font-size: 0.85rem;">
                      <option value="">-- Sélectionner un état --</option>
                      <option value="En attente" {{ old('etat', $document->etat) == 'En attente' ? 'selected' : '' }}>En attente</option>
                      <option value="Validé" {{ old('etat', $document->etat) == 'Validé' ? 'selected' : '' }}>Validé</option>
                      <option value="Rejeté" {{ old('etat', $document->etat) == 'Rejeté' ? 'selected' : '' }}>Rejeté</option>
                      <option value="Archivé" {{ old('etat', $document->etat) == 'Archivé' ? 'selected' : '' }}>Archivé</option>
                    </select>
                    @error('etat')<small class="text-danger" style="font-size: 0.75rem;">{{ $message }}</small>@enderror
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12 mb-2">
                  <div class="form-group mb-2">
                    <label style="font-size: 0.85rem; margin-bottom: 0.25rem;">Observation</label>
                    <textarea name="observation" class="form-control form-control-sm" rows="3" style="font-size: 0.85rem;">{{ old('observation', $document->observation) }}</textarea>
                    @error('observation')<small class="text-danger" style="font-size: 0.75rem;">{{ $message }}</small>@enderror
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Boutons d'action -->
          <div class="row mt-3">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-sm" style="font-size: 0.85rem;">
                <i class="fas fa-save mr-2"></i>Mettre à jour
              </button>
              <a href="{{ route('documents.index') }}" class="btn btn-secondary btn-sm" style="font-size: 0.85rem;">
                <i class="fas fa-arrow-left mr-2"></i>Retour
              </a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
@endsection
