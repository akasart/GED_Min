@extends('layouts.app')

@section('title', 'Niveaux de Confidentialité')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title">Niveaux de Confidentialité</h3>
      <a href="{{ route('confidentialities.create') }}" class="btn btn-primary">Nouveau Niveau</a>
    </div>
    <div class="card-body">
      @if($confidentialities->isEmpty())
        <p>Aucun niveau de confidentialité trouvé.</p>
      @else
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Code</th>
              <th>Libellé</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($confidentialities as $confidentiality)
              <tr>
                <td>{{ $confidentiality->id }}</td>
                <td>{{ $confidentiality->code ?? '-' }}</td>
                <td>{{ $confidentiality->label }}</td>
                <td>
                  <a href="{{ route('confidentialities.show', $confidentiality) }}" class="btn btn-sm btn-info">Voir</a>
                  <a href="{{ route('confidentialities.edit', $confidentiality) }}" class="btn btn-sm btn-warning">Éditer</a>
                  <form action="{{ route('confidentialities.destroy', $confidentiality) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Supprimer ce niveau ?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Supprimer</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      @endif
    </div>
  </div>
@endsection
