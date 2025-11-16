@extends('layouts.app')

@section('title', 'Détails du Service')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title">Détails du Service</h3>
      <div>
        <a href="{{ route('services.edit', $service) }}" class="btn btn-warning">Éditer</a>
        <a href="{{ route('services.index') }}" class="btn btn-secondary">Retour</a>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <table class="table table-borderless">
            <tr>
              <th>ID:</th>
              <td>{{ $service->id }}</td>
            </tr>
            <tr>
              <th>Nom:</th>
              <td>{{ $service->name }}</td>
            </tr>
            <tr>
              <th>Direction:</th>
              <td>{{ $service->direction ? $service->direction->libelle : '-' }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
