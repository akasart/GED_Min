<nav class="main-header navbar navbar-expand navbar-light shadow-sm" style="position:fixed; top:0; left:0; right:0; z-index:1030; height: 57px;">
  <!-- Left side -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link" id="sidebarToggle" href="#" style="padding: 0.5rem 1rem;">
        <i class="fas fa-bars" style="color:#586470; font-size: 1.1rem;"></i>
      </a>
    </li>
    <li class="nav-item d-none d-md-block">
      <a class="nav-link" href="{{ route('dashboard') }}" style="padding: 0.5rem 1rem;">
        <span style="font-weight: 500; color: #495057; font-size: 0.9rem;">Gestion Électronique de Documents</span>
      </a>
    </li>
  </ul>

  <!-- Center - Search -->
  <div class="navbar-nav mx-auto d-none d-lg-block" style="max-width: 400px; width: 100%;">
    <form class="form-inline w-100" action="{{ route('documents.search') }}" method="GET">
      <div class="input-group input-group-sm w-100">
        <input class="form-control form-control-navbar" type="search" name="q" placeholder="Recherche de documents..." style="border-radius: 20px 0 0 20px;" value="{{ request('q') }}">
        <div class="input-group-append">
          <button class="btn btn-navbar" type="submit" style="border-radius: 0 20px 20px 0; background-color: #007bff; color: white;">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>
    </form>
  </div>

  <!-- Right side -->
  <ul class="navbar-nav ml-auto">
    <!-- Notifications -->
    <li class="nav-item dropdown">
      <a class="nav-link position-relative" data-toggle="dropdown" href="#" style="padding: 0.5rem 0.75rem;">
        <i class="far fa-bell" style="font-size: 1.1rem; color: #586470;"></i>
        @php
          $unreadCount = \App\Models\Notification::getUnreadCount(auth()->id());
        @endphp
        @if($unreadCount > 0)
          <span class="navbar-badge badge badge-danger badge-pill" style="font-size: 0.6rem; padding: 0.2rem 0.4rem;">{{ $unreadCount }}</span>
        @endif
      </a>
      <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
        @php
          $notifications = \App\Models\Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        @endphp
        <span class="dropdown-header">{{ $notifications->count() }} Notification(s)</span>
        <div class="dropdown-divider"></div>
        @if($notifications->isEmpty())
          <a href="#" class="dropdown-item text-center text-muted">
            <i class="fas fa-bell-slash mr-2"></i> Aucune notification
          </a>
        @else
          @foreach($notifications as $notification)
            <a href="#" class="dropdown-item {{ !$notification->is_read ? 'font-weight-bold' : '' }}" onclick="markAsRead({{ $notification->id }})">
              <div class="d-flex align-items-start">
                <i class="fas fa-bell mr-2 mt-1"></i>
                <div class="flex-grow-1">
                  <div class="small">{{ $notification->titre }}</div>
                  <div class="text-muted" style="font-size: 0.75rem;">{{ Str::limit($notification->message, 50) }}</div>
                  <div class="text-muted" style="font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</div>
                </div>
              </div>
            </a>
            @if(!$loop->last)
              <div class="dropdown-divider"></div>
            @endif
          @endforeach
        @endif
        <div class="dropdown-divider"></div>
        <a href="#" class="dropdown-item dropdown-footer text-center">Voir toutes les notifications</a>
      </div>
    </li>
    
    <!-- User Profile -->
    <li class="nav-item dropdown">
      <a class="nav-link text-dark d-flex align-items-center" data-toggle="dropdown" href="#" style="padding: 0.5rem 0.75rem;">
        <div class="d-flex align-items-center">
          <i class="fas fa-user-circle mr-2" style="font-size: 1.3rem; color: #007bff;"></i>
          <div class="d-none d-md-block">
            <div style="font-weight: 500; font-size: 0.9rem;">{{ Auth::user()->username }}</div>
            @if(Auth::user()->agent)
              <small class="text-muted" style="font-size: 0.75rem;">{{ Auth::user()->agent->matricule }}</small>
            @endif
          </div>
        </div>
      </a>
      <div class="dropdown-menu dropdown-menu-right" style="min-width: 250px;">
        <div class="dropdown-header text-center" style="background-color: #f8f9fa;">
          <i class="fas fa-user-circle fa-3x text-primary mb-2"></i>
          <h6 class="mb-0" style="font-weight: 600;">{{ Auth::user()->username }}</h6>
          @if(Auth::user()->agent)
            <small class="text-muted">{{ Auth::user()->agent->matricule }}</small>
            <br>
            <small class="text-muted">{{ Auth::user()->agent->direction ?? 'Direction non définie' }}</small>
          @endif
        </div>
        <div class="dropdown-divider"></div>
        <a href="{{ route('profile') }}" class="dropdown-item">
          <i class="fas fa-user-cog mr-2"></i> Mon Profil
        </a>
        <a href="{{ route('settings') }}" class="dropdown-item">
          <i class="fas fa-cogs mr-2"></i> Paramètres
        </a>
        <div class="dropdown-divider"></div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
          @csrf
        </form>
        <a href="#" class="dropdown-item text-danger" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
          <i class="fas fa-power-off mr-2"></i> Déconnexion
        </a>
      </div>
    </li>
  </ul>
</nav>

<script>
function markAsRead(notificationId) {
  fetch(`/notifications/${notificationId}/mark-read`, {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      // Reload page to update notification count
      location.reload();
    }
  })
  .catch(error => {
    console.error('Error marking notification as read:', error);
  });
}
</script>