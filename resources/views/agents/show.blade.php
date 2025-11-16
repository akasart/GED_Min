@extends('layouts.app')

@section('title', 'Détails de l\'Agent')

@section('content')
  <div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
      <h3 class="card-title">Détails de l'Agent</h3>
      <div>
        <a href="{{ route('agents.edit', $agent) }}" class="btn btn-warning">Éditer</a>
        <a href="{{ route('agents.index') }}" class="btn btn-secondary">Retour</a>
      </div>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <table class="table table-borderless">
            <tr>
              <th>ID:</th>
              <td>{{ $agent->id }}</td>
            </tr>
            <tr>
              <th>Nom:</th>
              <td>{{ $agent->nom ?? '-' }}</td>
            </tr>
            <tr>
              <th>Prénom:</th>
              <td>{{ $agent->prenom ?? '-' }}</td>
            </tr>
            <tr>
              <th>Matricule:</th>
              <td>{{ $agent->matricule ?? '-' }}</td>
            </tr>
            <tr>
              <th>Ministère:</th>
              <td>{{ $agent->ministere_rattachement ?? '-' }}</td>
            </tr>
            <tr>
              <th>Direction:</th>
              <td>{{ $agent->direction ?? '-' }}</td>
            </tr>
            <tr>
              <th>Service:</th>
              <td>{{ $agent->service ?? '-' }}</td>
            </tr>
            <tr>
              <th>Fonction:</th>
              <td>{{ $agent->fonction ?? '-' }}</td>
            </tr>
            <tr>
              <th>Date d'Entrée:</th>
              <td>{{ $agent->date_entree ? $agent->date_entree->format('d/m/Y') : '-' }}</td>
            </tr>
          </table>
        </div>
        <div class="col-md-6">
          <h5>Informations de Connexion</h5>
          <table class="table table-borderless">
            <tr>
              <th>Nom d'utilisateur:</th>
              <td>{{ $agent->user->username ?? '-' }}</td>
            </tr>
            <tr>
              <th>Email:</th>
              <td>{{ $agent->user->email ?? '-' }}</td>
            </tr>
            <tr>
              <th>Rôle:</th>
              <td>
                @if($agent->user)
                  @php
                    $roleLabels = [
                      'admin' => 'Administrateur',
                      'rh' => 'RH',
                      'utilisateur' => 'Utilisateur'
                    ];
                    $roleBadges = [
                      'admin' => 'danger',
                      'rh' => 'warning',
                      'utilisateur' => 'primary'
                    ];
                  @endphp
                  <span class="badge badge-{{ $roleBadges[$agent->user->role] ?? 'secondary' }}">
                    {{ $roleLabels[$agent->user->role] ?? $agent->user->role }}
                  </span>
                @else
                  -
                @endif
              </td>
            </tr>
          </table>
        </div>
      </div>
    </div>
  </div>
@endsection
