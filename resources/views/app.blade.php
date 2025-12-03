<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'RaiseReport')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>

<body>
    @include('layout.navbar')

    <div class="flex m-5">
        @yield('content')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Jalankan di semua page
        document.addEventListener('DOMContentLoaded', function() {
            const key = 'scroll-position-' + location.pathname;

            // Restore scroll posisi dari sessionStorage
            const scrollPos = sessionStorage.getItem(key);
            if (scrollPos) {
                window.scrollTo(0, parseInt(scrollPos));
                sessionStorage.removeItem(key);
            }

            // Simpan scroll sebelum pindah halaman
            window.addEventListener('beforeunload', function() {
                sessionStorage.setItem(key, window.scrollY);
            });
        });
    </script>

</body>

</html>
