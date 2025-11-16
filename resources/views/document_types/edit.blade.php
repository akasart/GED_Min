@extends('layouts.app')

@section('title', 'Éditer le Type de Document')

@section('content')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Éditer le Type de Document</h3>
    </div>
    <div class="card-body">
      <form action="{{ route('document-types.update', $documentType) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
          <label for="libelle">Nom du Type</label>
          <input type="text" class="form-control @error('libelle') is-invalid @enderror" id="libelle" name="libelle" value="{{ old('libelle', $documentType->libelle) }}" required>
          @error('libelle')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Mettre à jour</button>
          <a href="{{ route('document-types.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
      </form>
    </div>
  </div>
@endsection
