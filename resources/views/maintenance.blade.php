<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coming Soon</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
            background: linear-gradient(135deg, #8B0000, #FF6347);
            color: white;
            font-family: 'Arial', sans-serif;
            overflow: hidden;
        }

        .blur-bg {
            position: absolute;
            width: 100%;
            height: 100%;
            backdrop-filter: blur(10px);
            background: rgba(0, 0, 0, 0.2);
        }

        .content {
            position: relative;
            z-index: 2;
        }

        .coming-soon-wrapper {
            height: 100vh;
        }

        .coming-image {
            max-width: 300px;
            filter: drop-shadow(0 10px 20px rgba(0, 0, 0, 0.5));
        }
    </style>
</head>
<body>
    <div class="blur-bg"></div>

    <div class="container d-flex justify-content-center align-items-center coming-soon-wrapper">
        <div class="text-center content">
            <img src="{{ asset('storage/img/logo.png') }}" alt="Coming Soon" class="coming-image mb-4">
            <h4 class="display-4 fw-bold">PT. Bangun Prestasi Bersama</h4>
            <h1 class="display-4 fw-bold">Segera Hadir</h1>
            <p class="lead">Website kami sedang dalam tahap pengembangan.<br>Stay tuned untuk sesuatu yang luar biasa!</p>
        </div>
    </div>

    <!-- Bootstrap JS (optional) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
