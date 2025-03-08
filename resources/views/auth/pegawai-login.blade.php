<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
<title>Login Pegawai</title>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

 <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #ff416c, #ff4b2b);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            animation: fadeIn 1s ease-in-out;
            text-align: center;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .btn-login {
            background-color: #ff0000; /* Merah */
            color: white;
            font-weight: bold;
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 45px;
        }
        .btn-login:hover {
            background-color: #cc0000;
        }
        .loading-spinner {
            display: none;
            width: 1.2rem;
            height: 1.2rem;
            border: 2px solid white;
            border-top: 2px solid transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 8px;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
    <script>
        $(document).ready(function() {
            $("#loginForm").on("submit", function() {
                $(".loading-spinner").css("display", "inline-block");
                $(".btn-text").hide();
            });
        });
    </script>
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="col-md-4 col-sm-8 col-10 login-container">
            <img src="{{ asset('storage/img/logo.png') }}" alt="Logo" width="100" class="mb-3">
            <h3 class="text-center text-danger fw-bold">Absensi Pegawai</h3>
            <form id="loginForm" action="{{ route('pegawai.login') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="nip" class="form-label">NIP</label>
                    <input type="tel" class="form-control" id="nip" name="nip" required autofocus>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-login w-100">
                    <span class="btn-text">Login</span>
                    <span class="loading-spinner"></span>
                </button>
            </form>
            {{-- <div class="text-center mt-3">
                <a href="{{ route('password.request') }}" class="text-decoration-none">Lupa Password?</a>
            </div> --}}
        </div>
    </div>
</body>
</html>

