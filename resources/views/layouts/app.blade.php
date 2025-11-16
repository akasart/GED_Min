<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>@yield('title', 'GED - Ministère du Travail')</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Design assets -->
  <link rel="stylesheet" href="{{ asset('design/plugins/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('design/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">
  <link rel="stylesheet" href="{{ asset('design/dist/css/adminlte.min.css') }}">
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@300;400;600;700&display=swap" rel="stylesheet">

  <style>
    body { font-family: 'Source Sans Pro', sans-serif; }
    .content-wrapper { min-height: 600px; margin-top: 57px; } /* Adjusted margin-top for fixed navbar */
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
    $(document).on('click', '.menu-item', function (e) {
      e.preventDefault();
      const url = $(this).attr('href');
      $('#content').load(url);
    });
  </script>
  @stack('scripts')
</body>
</html>
