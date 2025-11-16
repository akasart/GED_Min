@extends('layouts.app')

@section('title', 'Détails du Niveau de Confidentialité')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title">Détails du Niveau de Confidentialité</h3>
      <div>
        <a href="{{ route('confidentialities.edit', $confidentiality) }}" class="btn btn-warning">Éditer</a>
        <a href="{{ route('confidentialities.index') }}" class="btn btn-secondary">Retour</a>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <table class="table table-borderless">
            <tr>
              <th>ID:</th>
              <td>{{ $confidentiality->id }}</td>
            </tr>
            <tr>
              <th>Code:</th>
              <td>{{ $confidentiality->code ?? '-' }}</td>
            </tr>
            <tr>
              <th>Libellé:</th>
              <td>{{ $confidentiality->label }}</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
