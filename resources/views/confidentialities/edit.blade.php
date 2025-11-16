@extends('layouts.app')

@section('title', 'Éditer le Niveau de Confidentialité')

@section('content')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Éditer le Niveau de Confidentialité</h3>
    </div>
    <div class="card-body">
      <form action="{{ route('confidentialities.update', $confidentiality) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="code">Code</label>
              <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" value="{{ old('code', $confidentiality->code) }}">
              @error('code')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="label">Libellé</label>
              <input type="text" class="form-control @error('label') is-invalid @enderror" id="label" name="label" value="{{ old('label', $confidentiality->label) }}" required>
              @error('label')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Mettre à jour</button>
          <a href="{{ route('confidentialities.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
      </form>
    </div>
  </div>
@endsection
