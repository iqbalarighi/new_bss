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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
        background: linear-gradient(135deg, #ff416c, #ff4b2b);
        height: 100vh;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        padding: 20px; /* Tambahkan padding untuk layar kecil */
    }
    .login-container {
        background: white;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        animation: fadeIn 1s ease-in-out;
        text-align: center;
        width: 100%;
        max-width: 400px; /* Batasi ukuran maksimal */
    }
        @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    @media (max-width: 576px) {
        .login-container {
            padding: 20px; /* Kurangi padding pada layar kecil */
        }
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
        }
        .btn-login:disabled {
            background-color: #ff0000 !important; /* Tetap merah */
            color: white !important; /* Tetap putih */
            opacity: 0.7; /* Sedikit transparan untuk indikasi */
            cursor: not-allowed;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }

        .password-wrapper {
            position: relative;
        }
        .password-wrapper i {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 20px;
        }
        .logo-container img {
            width: 180px;
            filter: drop-shadow(0px 4px 6px rgba(0, 0, 0, 0.3)); /* Efek shadow */
            border-radius: 50%;
        }
    </style>
<script>
    $(document).ready(function() {
        $("#loginForm").on("submit", function() {
            $(".loading-spinner").css("display", "inline-block");
            $(".btn-text").hide();
            $(".btn-login").prop("disabled", true).css("background-color", "#ff0000"); // Jaga warna tetap merah
        });
    });
</script>
</head>
<body>
    @if(Session::get('error'))
<script type="text/javascript">
            Swal.fire({
                icon: 'error',
                title: "{{ session('error') }}",
                confirmButtonColor: '#d33',
                confirmButtonText: 'OK',
                allowOutsideClick: false // Mencegah klik di luar untuk menutup
            });
</script>
        @endif
    <div class="login-container">
    <div class="logo-container">
        <img src="{{asset('storage/img/logo.png')}}" alt="Logo" width="200" class="mb-2">
    </div>
        <h3 class="text-center text-danger fw-bold">Absensi Pegawai</h3>
        <form id="loginForm" action="{{ route('absen.login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="nip" class="form-label">NIP</label>
                <input type="tel" class="form-control" id="nip" name="nip" oninput="validateInput(event)" placeholder="Nomor Induk Pegawai" required autofocus>
            </div>
            <div class="mb-3 password-wrapper">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                    <i id="togglePassword" class="bi bi-eye mt-3"></i>
            </div>
            <div class="mb-3 form-check text-start">
                <input type="checkbox" class="form-check-input" id="remember" name="remember">
                <label class="form-check-label" for="remember">Remember Me</label>
            </div>
            <div class="d-flex justify-content-center">
                <button type="submit" class="btn btn-login">
                    <span class="btn-text">Login</span>
                    <span class="loading-spinner"></span>
                </button>
            </div>
        </form>
    </div>
</body>
<script>
    $("#togglePassword").on("click", function() {
        var passwordField = $("#password");
        var type = passwordField.attr("type") === "password" ? "text" : "password";
        passwordField.attr("type", type);
        $(this).toggleClass("bi-eye bi-eye-slash");
    });
</script>
    <script>
        function validateInput(event) {
            let input = event.target;
            input.value = input.value.replace(/\D/g, ''); // Hanya izinkan angka
        }
    </script>    <script>
        function validateInput(event) {
            let input = event.target;
            input.value = input.value.replace(/\D/g, ''); // Hanya izinkan angka
        }
    </script>

</html>
