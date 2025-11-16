@extends('layouts.app')

@section('title', 'Directions')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title">Directions</h3>
      <a href="{{ route('directions.create') }}" class="btn btn-primary">Nouvelle Direction</a>
    </div>
    <div class="card-body">
      @if($directions->isEmpty())
        <p>Aucune direction trouvée.</p>
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
            @foreach($directions as $direction)
              <tr>
                <td>{{ $direction->id }}</td>
                <td>{{ $direction->code ?? '-' }}</td>
                <td>{{ $direction->libelle }}</td>
                <td>
                  <a href="{{ route('directions.show', $direction) }}" class="btn btn-sm btn-info">Voir</a>
                  <a href="{{ route('directions.edit', $direction) }}" class="btn btn-sm btn-warning">Éditer</a>
                  <form action="{{ route('directions.destroy', $direction) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Supprimer cette direction ?')">
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
