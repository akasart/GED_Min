@extends('layouts.app')

@section('title', 'Créer un Service')

@section('content')
  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Créer un Service</h3>
    </div>
    <div class="card-body">
      <form action="{{ route('services.store') }}" method="POST">
        @csrf
        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="name">Nom du Service</label>
              <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="direction_id">Direction</label>
              <select class="form-control @error('direction_id') is-invalid @enderror" id="direction_id" name="direction_id">
                <option value="">Sélectionner une direction</option>
                @foreach($directions as $direction)
                  <option value="{{ $direction->id }}" {{ old('direction_id') == $direction->id ? 'selected' : '' }}>
                    {{ $direction->libelle }}
                  </option>
                @endforeach
              </select>
              @error('direction_id')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
          </div>
        </div>
        <div class="form-group">
          <button type="submit" class="btn btn-primary">Créer</button>
          <a href="{{ route('services.index') }}" class="btn btn-secondary">Annuler</a>
        </div>
      </form>
    </div>
  </div>
@endsection
