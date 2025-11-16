@extends('layouts.app')

@section('title', 'Services')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title">Services</h3>
      <a href="{{ route('services.create') }}" class="btn btn-primary">Nouveau Service</a>
    </div>
    <div class="card-body">
      @if($services->isEmpty())
        <p>Aucun service trouvé.</p>
      @else
        <table class="table table-striped">
          <thead>
            <tr>
              <th>#</th>
              <th>Nom</th>
              <th>Direction</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            @foreach($services as $service)
              <tr>
                <td>{{ $service->id }}</td>
                <td>{{ $service->name }}</td>
                <td>{{ $service->direction ? $service->direction->libelle : '-' }}</td>
                <td>
                  <a href="{{ route('services.show', $service) }}" class="btn btn-sm btn-info">Voir</a>
                  <a href="{{ route('services.edit', $service) }}" class="btn btn-sm btn-warning">Éditer</a>
                  <form action="{{ route('services.destroy', $service) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Supprimer ce service ?')">
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
