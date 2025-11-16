@extends('layouts.app')

@section('title', 'Liste des Utilisateurs')

@section('content')
<div class="container-fluid" style="font-size: 0.875rem;">
  <!-- Header Section -->
  <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
    <h5 style="font-size: 1.1rem; margin: 0;">Liste des Utilisateurs</h5>
    <nav aria-label="breadcrumb">
      <ol class="breadcrumb mb-0" style="font-size: 0.8rem;">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Accueil</a></li>
        <li class="breadcrumb-item active">Utilisateurs</li>
      </ol>
    </nav>
  </div>

  <!-- Users Table -->
  <div class="card">
    <div class="card-header bg-light py-2">
      <h6 class="mb-0" style="font-size: 0.9rem;">Tous les utilisateurs</h6>
    </div>
    <div class="card-body p-0">
      @if($users->isEmpty())
        <div class="text-center py-4">
          <i class="fas fa-users fa-2x text-muted mb-2" style="font-size: 1.5rem;"></i>
          <p class="text-muted mb-0" style="font-size: 0.85rem;">Aucun utilisateur trouvé.</p>
        </div>
      @else
        <div class="table-responsive">
          <table class="table table-hover mb-0" style="font-size: 0.85rem;">
            <thead class="thead-light">
              <tr>
                <th style="font-size: 0.8rem; padding: 0.5rem;">ID</th>
                <th style="font-size: 0.8rem; padding: 0.5rem;">Login (Matricule)</th>
                <th style="font-size: 0.8rem; padding: 0.5rem;">Nom d'utilisateur</th>
                <th style="font-size: 0.8rem; padding: 0.5rem;">Email</th>
                <th style="font-size: 0.8rem; padding: 0.5rem;">Mot de passe</th>
                <th style="font-size: 0.8rem; padding: 0.5rem;">Rôle</th>
                <th style="font-size: 0.8rem; padding: 0.5rem;">Agent</th>
                <th style="font-size: 0.8rem; padding: 0.5rem;">Date de création</th>
              </tr>
            </thead>
            <tbody>
              @foreach($users as $user)
              <tr>
                <td style="font-size: 0.85rem;">{{ $user->id }}</td>
                <td style="font-size: 0.85rem;">
                  @if($user->agent)
                    <span class="text-primary font-weight-bold">{{ $user->agent->matricule }}</span>
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td style="font-size: 0.85rem;">
                  <strong>{{ $user->username }}</strong>
                </td>
                <td style="font-size: 0.85rem;">{{ $user->email ?? '-' }}</td>
                <td style="font-size: 0.85rem;">
                  <span class="badge badge-secondary" title="Mot de passe hashé (non affichable)">
                    <i class="fas fa-lock"></i> Hashé
                  </span>
                  @if(auth()->user()->isAdmin())
                    <button class="btn btn-sm btn-warning ml-1" onclick="resetPassword({{ $user->id }}, '{{ $user->username }}')" title="Réinitialiser le mot de passe">
                      <i class="fas fa-key"></i>
                    </button>
                  @endif
                </td>
                <td style="font-size: 0.85rem;">
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
                  <span class="badge badge-{{ $roleBadges[$user->role] ?? 'secondary' }}">
                    {{ $roleLabels[$user->role] ?? $user->role }}
                  </span>
                </td>
                <td style="font-size: 0.85rem;">
                  @if($user->agent)
                    {{ $user->agent->nom }} {{ $user->agent->prenom }}
                  @else
                    <span class="text-muted">-</span>
                  @endif
                </td>
                <td style="font-size: 0.85rem;">
                  {{ $user->created_at ? $user->created_at->format('d/m/Y') : '-' }}
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        <div class="card-footer py-2">
          <div class="d-flex justify-content-between align-items-center">
            <div style="font-size: 0.8rem;">
              Affichage de {{ $users->firstItem() }} à {{ $users->lastItem() }} sur {{ $users->total() }} utilisateurs
            </div>
            <div>
              {{ $users->links() }}
            </div>
          </div>
        </div>
      @endif
    </div>
  </div>
</div>

<!-- Reset Password Modal -->
@if(auth()->user()->isAdmin())
<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Réinitialiser le mot de passe</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>
      <form id="resetPasswordForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-body">
          <div class="form-group">
            <label>Utilisateur:</label>
            <input type="text" id="resetUsername" class="form-control" readonly>
          </div>
          <div class="form-group">
            <label for="newPassword">Nouveau mot de passe *</label>
            <input type="password" class="form-control" id="newPassword" name="password" required minlength="6">
            <small class="form-text text-muted">Minimum 6 caractères</small>
          </div>
          <div class="form-group">
            <label for="confirmPassword">Confirmer le mot de passe *</label>
            <input type="password" class="form-control" id="confirmPassword" name="password_confirmation" required minlength="6">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          <button type="submit" class="btn btn-primary">Réinitialiser</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endif

<script>
function resetPassword(userId, username) {
  document.getElementById('resetUsername').value = username;
  document.getElementById('resetPasswordForm').action = '/users/' + userId + '/reset-password';
  $('#resetPasswordModal').modal('show');
}

// Validate password confirmation
document.getElementById('resetPasswordForm')?.addEventListener('submit', function(e) {
  const password = document.getElementById('newPassword').value;
  const confirmPassword = document.getElementById('confirmPassword').value;
  
  if (password !== confirmPassword) {
    e.preventDefault();
    alert('Les mots de passe ne correspondent pas!');
    return false;
  }
});
</script>
@endsection

