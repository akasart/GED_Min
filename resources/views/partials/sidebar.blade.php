<aside class="main-sidebar sidebar-dark-primary elevation-4">
  <div class="brand-container text-center py-2">
    <img src="{{ asset('design/img/logoMIN.png') }}" alt="Logo" class="brand-image img-circle elevation-3 mb-1" style="width:70px;">
    <span class="brand-text text-white d-block" style="font-size: 0.75rem; line-height: 1.2;">Ministère du Travail<br>de l'Emploi et de la Fonction Publique</span>
  </div>

  <div class="sidebar">
    <nav class="mt-2">
      <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" style="font-size: 0.85rem;">
        @foreach(config('navigation') as $item)
          @php
            // Skip admin sections for non-admin users
            if (isset($item['label']) && $item['label'] === 'Administration' && !auth()->user()->isAdmin()) {
              continue;
            }
          @endphp
          @if(isset($item['children']))
            <li class="nav-item has-treeview {{ collect($item['children'])->pluck('route')->contains(function($r){ return request()->routeIs($r); }) ? 'menu-open' : '' }}">
              <a href="#" class="nav-link {{ collect($item['children'])->pluck('route')->contains(function($r){ return request()->routeIs($r); }) ? 'active' : '' }}">
                <i class="nav-icon {{ $item['icon'] ?? 'fas fa-circle' }}" style="font-size: 0.9rem;"></i>
                <p style="font-size: 0.85rem;">{{ $item['label'] }} <i class="right fas fa-angle-left"></i></p>
              </a>
              <ul class="nav nav-treeview">
                @foreach($item['children'] as $child)
                  @php
                    // Skip admin-only routes for non-admin users
                    if (in_array($child['route'], ['documents.pending', 'admin.index']) && !auth()->user()->isAdmin()) {
                      continue;
                    }
                  @endphp
                  <li class="nav-item">
                    @if(Illuminate\Support\Facades\Route::has($child['route']))
                      <a href="{{ route($child['route']) }}" class="nav-link {{ request()->routeIs($child['route']) ? 'active' : '' }}">
                    @else
                      <a href="#" class="nav-link">
                    @endif
                        <i class="far fa-circle nav-icon" style="font-size: 0.8rem;"></i>
                        <p style="font-size: 0.8rem;">{{ $child['label'] }}</p>
                      </a>
                  </li>
                @endforeach
              </ul>
            </li>
          @else
            <li class="nav-item">
              @if(Illuminate\Support\Facades\Route::has($item['route']))
                <a href="{{ route($item['route']) }}" class="nav-link {{ request()->routeIs($item['route']) ? 'active' : '' }}">
              @else
                <a href="#" class="nav-link">
              @endif
                  <i class="nav-icon {{ $item['icon'] ?? 'fas fa-circle' }}" style="font-size: 0.9rem;"></i>
                  <p style="font-size: 0.85rem;">{{ $item['label'] }}</p>
                </a>
            </li>
          @endif
        @endforeach
      </ul>
    </nav>
  </div>
</aside>
