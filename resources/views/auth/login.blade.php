<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Ministère du Travail, de l'Emploi et de la Fonction Publique</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('design/plugins/fontawesome-free/css/all.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{ asset('design/plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('design/dist/css/adminlte.min.css') }}">
  <!-- Google Font -->
  <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@400;600;700&display=swap" rel="stylesheet">

  <style>
    body {
      background: linear-gradient(to bottom right, #6a89cc, #b8e994);
      font-family: 'Source Sans Pro', sans-serif;
    }
    .login-box {
      width: 400px;
    }
    .login-logo img {
      width: 120px;
      margin-bottom: 10px;
    }
    .card {
      border-radius: 15px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    .login-card-body {
      border-radius: 15px;
    }
    .login-box-msg {
      font-weight: 600;
      font-size: 1.2rem;
      color: #2f3640;
    }
    .btn-primary {
      background-color: #2e86de;
      border-color: #2e86de;
      border-radius: 25px;
      transition: 0.3s;
    }
    .btn-primary:hover {
      background-color: #1e3799;
      border-color: #1e3799;
    }
    .forgot-password {
      text-align: center;
      margin-top: 15px;
    }
    .forgot-password a {
      color: #2e86de;
      text-decoration: none;
    }
    .forgot-password a:hover {
      text-decoration: underline;
    }
  </style>
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="card">
      <div class="card-body login-card-body text-center">
        <div class="login-logo">
          <img src="{{ asset('design/img/logoMIN.png') }}" alt="Logo du Ministère">
        </div>
      
        <p class="login-box-msg"> Connexion</p>

        <form action="{{ route('login') }}" method="post">
          @csrf

          @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
          @endif
          <div class="input-group mb-3">
            <input type="text" name="matricule" value="{{ old('matricule') }}" class="form-control @error('matricule') is-invalid @enderror" placeholder="Matricule" required autofocus>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-id-card"></span>
              </div>
            </div>
            @error('matricule')
              <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
          </div>

          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mot de passe" required>
            @error('password')
              <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
            @enderror
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>

          <div class="row justify-content-center">
            <div class="col-8">
              <button type="submit" class="btn btn-primary btn-block">Se connecter</button>
            </div>
          </div>
        </form>

        <div class="forgot-password">
          @if (Route::has('password.request'))
            <a href="{{ route('password.request') }}">Mot de passe oublié ?</a>
          @endif
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="{{ asset('design/plugins/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('design/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('design/dist/js/adminlte.min.js') }}"></script>
</body>
</html>
