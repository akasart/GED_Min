@extends('layouts.app')

@section('title', 'Détails du Type de Document')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title">Détails du Type de Document</h3>
      <div>
        <a href="{{ route('document-types.edit', $documentType) }}" class="btn btn-warning">Éditer</a>
        <a href="{{ route('document-types.index') }}" class="btn btn-secondary">Retour</a>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <table class="table table-borderless">
            <tr>
              <th>ID:</th>
              <td>{{ $documentType->id }}</td>
            </tr>
            <tr>
              <th>Nom:</th>
              <td>{{ $documentType->libelle }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
