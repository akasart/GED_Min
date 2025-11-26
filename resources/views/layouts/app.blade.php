<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title', 'GED - Ministère du Travail')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Preload critical resources for faster loading -->
  <link rel="preload" href="{{ asset('design/img/logoMIN.png') }}" as="image">
  <link rel="preload" href="{{ asset('design/plugins/fontawesome-free/css/all.min.css') }}" as="style">
  <link rel="preload" href="{{ asset('design/plugins/fontawesome-free/webfonts/fa-solid-900.woff2') }}" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="{{ asset('design/plugins/fontawesome-free/webfonts/fa-regular-400.woff2') }}" as="font" type="font/woff2" crossorigin>

  <!-- Design assets -->
  <link rel="stylesheet" href="{{ asset('design/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('design/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <link rel="stylesheet" href="{{ asset('design/dist/css/adminlte.min.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Source Sans Pro', sans-serif; }
    .content-wrapper { min-height: 600px; margin-top: 57px; } /* Adjusted margin-top for fixed navbar */
    
    /* Responsive Design */
    @media (max-width: 768px) {
      .content-wrapper { margin-top: 57px; margin-left: 0 !important; }
      .main-sidebar { 
        position: fixed !important; 
        z-index: 1040;
        left: 0;
      }
      .navbar-nav .nav-link { padding: 0.5rem 0.5rem !important; }
      .card-body { padding: 0.75rem !important; }
      .container-fluid { padding-left: 10px; padding-right: 10px; }
      
      /* Sidebar behavior on mobile using AdminLTE classes */
      body.sidebar-collapse .main-sidebar {
        margin-left: -250px;
      }
      
      body:not(.sidebar-collapse) .main-sidebar {
        margin-left: 0;
      }
      
      /* Overlay when sidebar is open on mobile */
      body:not(.sidebar-collapse)::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        z-index: 1039;
        display: block;
      }
      
      body.sidebar-collapse::before {
        display: none;
      }
    }
    
    @media (max-width: 576px) {
      .brand-text { font-size: 0.65rem !important; }
      .navbar .input-group { max-width: 200px; }
      h1, .h1 { font-size: 1.5rem; }
      h2, .h2 { font-size: 1.3rem; }
      h3, .h3 { font-size: 1.1rem; }
    }
    
    /* Smooth transitions */
    .main-sidebar { 
      transition: margin-left 0.3s ease-in-out;
    }
  </style>
</head>
<body class="hold-transition sidebar-mini layout-fixed">

  <div class="wrapper">
    <!-- Sidebar -->
    @include('partials.sidebar')

    <!-- Main Content -->
    <div class="content-wrapper">
      <!-- Navbar -->
      @include('partials.navbar')

      <!-- Dynamic Content -->
      <div id="content" class="content">
        <div class="container-fluid pt-3">
          {{-- Session flash messages --}}
          @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
          @endif
          @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif

          @yield('content')
        </div>
      </div>
    </div>
  </div>

  <footer class="main-footer text-center">
    <strong>&copy; {{ date('Y') }} MTEFoP</strong>
  </footer>

  <!-- Scripts -->
  <script src="{{ asset('design/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('design/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('design/dist/js/adminlte.min.js') }}"></script>
  
  <script>
    // SPA Navigation - Load only content without reloading navbar/sidebar/images
    $(document).ready(function() {
      // Intercept all navigation links
      $(document).on('click', 'a[href]:not([target="_blank"]):not([href^="#"]):not([href^="javascript:"]):not(.no-ajax)', function(e) {
        const url = $(this).attr('href');
        
        // Skip external links and logout
        if (url.includes('logout') || url.startsWith('http') || url.startsWith('//')) {
          return true;
        }
        
        e.preventDefault();
        loadContent(url);
      });
      
      // Handle browser back/forward buttons
      window.addEventListener('popstate', function(e) {
        if (e.state && e.state.url) {
          loadContent(e.state.url, false);
        }
      });
      
      // Save initial state
      history.replaceState({ url: window.location.href }, '', window.location.href);
      
      function loadContent(url, pushState = true) {
        // Show loading indicator
        $('#content').html('<div class="text-center py-5"><i class="fas fa-spinner fa-spin fa-3x text-primary"></i><p class="mt-3">Chargement...</p></div>');
        
        // Load content via AJAX
        $.ajax({
          url: url,
          type: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest'
          },
          success: function(response) {
            // Extract content from response
            const $response = $('<div>').html(response);
            const $newContent = $response.find('#content');
            
            if ($newContent.length > 0) {
              $('#content').html($newContent.html());
            } else {
              // If no #content found, use the whole response
              $('#content').html(response);
            }
            
            // Update page title
            const newTitle = $response.filter('title').text() || $response.find('title').text();
            if (newTitle) {
              document.title = newTitle;
            }
            
            // Update browser history
            if (pushState) {
              history.pushState({ url: url }, '', url);
            }
            
            // Scroll to top
            window.scrollTo(0, 0);
            
            // Reinitialize any plugins if needed
            if (typeof $.fn.select2 !== 'undefined') {
              $('.select2').select2();
            }
          },
          error: function(xhr, status, error) {
            $('#content').html(
              '<div class="alert alert-danger m-3">' +
              '<i class="fas fa-exclamation-triangle mr-2"></i>' +
              'Erreur lors du chargement de la page. ' +
              '<a href="' + url + '" class="alert-link no-ajax">Cliquez ici pour réessayer</a>' +
              '</div>'
            );
          }
        });
      }
    });
  </script>
  @stack('scripts')
</body>
</html>
