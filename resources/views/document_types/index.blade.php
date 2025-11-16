@extends('layouts.app')

@section('title', 'Types de Documents')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title">Types de Documents</h3>
      <a href="{{ route('document-types.create') }}" class="btn btn-primary">Nouveau Type</a>
    </div>
    <div class="card-body">
      @if($types->isEmpty())
        <p>Aucun type de document trouvé.</p>
      @else
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Nom</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($types as $type)
              <tr>
                <td>{{ $type->id }}</td>
                <td>{{ $type->libelle }}</td>
                <td>
                  <a href="{{ route('document-types.show', $type) }}" class="btn btn-sm btn-info">Voir</a>
                  <a href="{{ route('document-types.edit', $type) }}" class="btn btn-sm btn-warning">Éditer</a>
                  <form action="{{ route('document-types.destroy', $type) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Supprimer ce type ?')">
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
