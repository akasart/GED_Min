@extends('layouts.app')

@section('title', 'Détails de la Direction')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title">Détails de la Direction</h3>
      <div>
        <a href="{{ route('directions.edit', $direction) }}" class="btn btn-warning">Éditer</a>
        <a href="{{ route('directions.index') }}" class="btn btn-secondary">Retour</a>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <table class="table table-borderless">
            <tr>
              <th>ID:</th>
              <td>{{ $direction->id }}</td>
            </tr>
            <tr>
              <th>Code:</th>
              <td>{{ $direction->code ?? '-' }}</td>
            </tr>
            <tr>
              <th>Libellé:</th>
              <td>{{ $direction->libelle }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
