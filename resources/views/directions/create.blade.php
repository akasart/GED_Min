@extends('layouts.app')

@section('title', 'Créer une Direction')

@section('content')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Créer une Direction</h3>
    </div>
    <div class="card-body">
      <form action="{{ route('directions.store') }}" method="POST">
        @csrf
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="code">Code</label>
              <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code') }}">
              @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="libelle">Libellé</label>
              <input type="text" class="form-control @error('libelle') is-invalid @enderror" id="libelle" name="libelle" value="{{ old('libelle') }}" required>
              @error('libelle')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Créer</button>
          <a href="{{ route('directions.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
      </form>
    </div>
  </div>
@endsection
