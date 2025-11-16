@extends('layouts.app')

@section('title', 'Liste des Agents')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title">Liste des Agents</h3>
      <a href="{{ route('agents.create') }}" class="btn btn-primary">Nouvel Agent</a>
    </div>
    <div class="card-body">
      @if($agents->isEmpty())
        <p>Aucun agent trouvé.</p>
      @else
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Matricule</th>
              <th>Nom</th>
              <th>Prénom</th>
              <th>Direction</th>
              <th>Service</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($agents as $agent)
              <tr>
                <td>{{ $agent->id }}</td>
                <td>{{ $agent->matricule }}</td>
                <td>{{ $agent->nom }}</td>
                <td>{{ $agent->prenom ?? '-' }}</td>
                <td>{{ $agent->direction ?? '-' }}</td>
                <td>{{ $agent->service ?? '-' }}</td>
                <td>
                  <a href="{{ route('agents.show', $agent) }}" class="btn btn-sm btn-info">Voir</a>
                  <a href="{{ route('agents.edit', $agent) }}" class="btn btn-sm btn-warning">Éditer</a>
                  <form action="{{ route('agents.destroy', $agent) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Supprimer cet agent ?')">
                    @csrf @method('DELETE')
                    <button class="btn btn-sm btn-danger">Supprimer</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
        {{ $agents->links() }}
      @endif
    </div>
  </div>
@endsection
