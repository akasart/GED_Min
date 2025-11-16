@extends('layouts.app')

@section('title', 'Créer un Type de Document')

@section('content')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Créer un Type de Document</h3>
    </div>
    <div class="card-body">
      <form action="{{ route('document-types.store') }}" method="POST">
        @csrf
        <div class="form-group">
          <label for="libelle">Nom du Type</label>
          <input type="text" class="form-control @error('libelle') is-invalid @enderror" id="libelle" name="libelle" value="{{ old('libelle') }}" required>
          @error('libelle')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Créer</button>
          <a href="{{ route('document-types.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
      </form>
    </div>
  </div>
@endsection
