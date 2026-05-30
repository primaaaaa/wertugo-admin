<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Wertugo</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa; /* Warna background abu-abu terang */
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .error-card {
            max-width: 550px;
            width: 100%;
            border-radius: 2rem;
            border: none;
        }
        .error-img {
            height: 240px;
            object-fit: cover;
            border-radius: 1.5rem;
        }
        .btn-wrapper {
            display: flex;
            justify-content: center;
            gap: 16px; /* Jarak antar tombol */
        }
        .btn-custom {
            border-radius: 50rem;
            padding: 10px 24px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s;
            white-space: nowrap; /* KUNCI: Mencegah teks tombol patah ke bawah */
        }
        .btn-primary-custom {
            background-color: #006c07;
            color: white;
            border: 2px solid #006c07;
        }
        .btn-primary-custom:hover {
            background-color: #005005;
            border-color: #005005;
            color: white;
        }
        .btn-outline-custom {
            background-color: white;
            color: #006c07;
            border: 2px solid #006c07;
        }
        .btn-outline-custom:hover {
            background-color: rgba(0, 108, 7, 0.05);
            color: #006c07;
        }
        
        /* Hanya menumpuk tombol di layar HP yang SANGAT KECIL (di bawah 400px) */
        @media (max-width: 400px) {
            .btn-wrapper {
                flex-direction: column;
                gap: 10px;
            }
            .btn-custom {
                width: 100%;
            }
        }
    </style>
</head>
<body class="p-4">

    <div class="card shadow-sm error-card p-4 p-md-5 text-center bg-white">
        <img src="@yield('image')" alt="Error Illustration" class="img-fluid error-img mb-4 w-100 shadow-sm">
        
        <h2 class="fw-bold mb-3" style="color: #006c07; letter-spacing: -0.5px;">@yield('heading')</h2>
        
        <p class="text-secondary mb-4" style="font-size: 0.95rem; line-height: 1.6;">
            @yield('message')
        </p>
        
        <div class="btn-wrapper mt-2">
            @yield('buttons')
        </div>
    </div>

</body>
</html>