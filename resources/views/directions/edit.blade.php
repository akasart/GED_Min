@extends('layouts.app')

@section('title', 'Éditer la Direction')

@section('content')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Éditer la Direction</h3>
    </div>
    <div class="card-body">
      <form action="{{ route('directions.update', $direction) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="code">Code</label>
              <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $direction->code) }}">
              @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="libelle">Libellé</label>
              <input type="text" class="form-control @error('libelle') is-invalid @enderror" id="libelle" name="libelle" value="{{ old('libelle', $direction->libelle) }}" required>
              @error('libelle')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Mettre à jour</button>
          <a href="{{ route('directions.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
      </form>
    </div>
  </div>
@endsection
