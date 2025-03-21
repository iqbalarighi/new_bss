<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    <title>Login Pegawai</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            background: linear-gradient(135deg, #ff0000, #ff4d4d, #ff8080);
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            text-align: center;
        }
        .card {
            width: 100%;
            max-width: 400px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
            animation: fadeInUp 1s ease-in-out;
        }
        .logo-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .logo-container img {
            width: 180px;
            filter: drop-shadow(0px 4px 6px rgba(0, 0, 0, 0.3)); /* Efek shadow */
            border-radius: 50%;
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
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

        .login-container {
        margin-top: -30px; 
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.2);
        animation: fadeIn 1s ease-in-out;
        text-align: center;
        width: 100%;
        max-width: 400px; /* Batasi ukuran maksimal */
    }
    </style>
</head>
<body>
    <div class="container">
        <div class="col-md-6 col-sm-8 col-10 mx-auto login-container">
            
            <div class="card">
                <div class="card-header bg-white text-danger text-center fw-bold">
                    <div class="logo-container">
                        <img src="{{asset('storage/img/logo.png')}}" alt="Logo" width="200" class="mb-2">
                    </div>
                </div>
                <div class="card-body">

                @error('email')
                <script type="text/javascript">
                            Swal.fire({
                                icon: 'error',
                                title: "{{ $errors->get('email')[0] }}",
                                confirmButtonColor: '#d33',
                                confirmButtonText: 'OK',
                                allowOutsideClick: false // Mencegah klik di luar untuk menutup
                            });
                </script>
                @enderror

                    <form method="POST" action="{{ route('login') }}" id="loginForm">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label text-danger">Email Address</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" required autofocus>
                        </div>
                        <div class="mb-3 password-wrapper">
                            <label for="password" class="form-label text-danger">Password</label>
                            <input id="password" type="password" class="form-control @error('email') is-invalid @enderror" name="password" required>
                            <i id="togglePassword" class="bi bi-eye mt-3"></i>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <button type="submit" id="loginButton" class="btn btn-danger w-100 d-flex align-items-center justify-content-center">
                            <span class="btn-text">Login</span>
                            <div class="spinner-border spinner-border-sm text-light ms-2 d-none" role="status" id="loadingSpinner"></div>
                        </button>
                    </form>
                    <div class="mt-3 text-center">
                        <a href="{{ route('pegawai.login') }}" class="btn btn-secondary w-100">Login Absen</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            let loginForm = document.getElementById("loginForm");
            let loginButton = document.getElementById("loginButton");
            let loadingSpinner = document.getElementById("loadingSpinner");
            let btnText = document.querySelector(".btn-text");

            if (loginForm) {
                loginForm.addEventListener("submit", function () {
                    loginButton.disabled = true;
                    loadingSpinner.classList.remove("d-none");
                    btnText.style.display = "none";
                });
            }
        });
    </script>
    <script type="text/javascript">
    
    $("#togglePassword").on("click", function() {
    var passwordField = $("#password");
    var type = passwordField.attr("type") === "password" ? "text" : "password";
    passwordField.attr("type", type);
    $(this).toggleClass("bi-eye bi-eye-slash");
});
</script>
</body>
</html>
