@extends('layouts.app')
@section('content')
<div class="container d-flex justify-content-center align-items-center min-vh-100" style="background: linear-gradient(135deg, #b71c1c, #d32f2f);">
    <div class="col-md-6 col-sm-8 col-10" style="margin-top: -50px;">
        <div class="text-center mb-4" style="margin-top: -50px;">
            <img src="{{ asset('storage/img/logo.png') }}" alt="Logo" width="100" class="animated fadeIn">
        </div>
        <div class="card shadow-lg rounded-lg animated fadeInUp" style="border: none;">
            <div class="card-header bg-danger text-white text-center fw-bold">{{ __('Login Pegawai') }}</div>
            <div class="card-body">
                @if(session('error'))
                    <div class="alert alert-danger" role="alert">{{ session('error') }}</div>
                @endif
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label text-danger">{{ __('Email Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label text-danger">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <div class="mb-3 form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label class="form-check-label text-danger" for="remember">{{ __('Remember Me') }}</label>
                    </div>
                    <button type="submit" id="loginButton" class="btn btn-danger w-100 d-flex align-items-center justify-content-center">
                        <span class="btn-text">{{ __('Login') }}</span>
                        <div class="spinner-border spinner-border-sm text-light ms-2 d-none" role="status" id="loadingSpinner"></div>
                    </button>
                    <div class="text-center mt-3">
                        <a class="btn btn-light text-danger border-danger w-100" href="{{ route('pegawai.login') }}">{{ __('Login sebagai Pegawai') }}</a>
                    </div>
                    @if (Route::has('password.request'))
                        <div class="text-center mt-3">
                            <a class="text-decoration-none text-light" href="{{ route('password.request') }}">{{ __('Forgot Your Password?') }}</a>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<style>
    .animated {
        animation-duration: 1s;
        animation-fill-mode: both;
    }
    .fadeIn {
        animation-name: fadeIn;
    }
    .fadeInUp {
        animation-name: fadeInUp;
    }
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function () {
    let loginForm = document.getElementById("loginForm");
    let loginButton = document.getElementById("loginButton");
    let loadingSpinner = document.getElementById("loadingSpinner");
    let btnText = document.querySelector(".btn-text");

    if (loginForm) {
        loginForm.addEventListener("submit", function () {
            // Disable button and show spinner
            loginButton.disabled = true;
            loadingSpinner.classList.remove("d-none");
            btnText.style.display = "none";
        });
    }
});
</script>
@endsection
