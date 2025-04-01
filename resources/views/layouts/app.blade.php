<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <script>
        window.__WS_TOKEN__ = "{{ csrf_token() }}";
    </script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @stack('head-scripts')
</head>

<body>
    <header>
        @yield('header')
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        @yield('footer')
    </footer>

    @stack('scripts')

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Display Laravel validation errors with SweetAlert
            @if ($errors->any())
                let errorMessage = '<ul class="text-left">';
                @foreach ($errors->all() as $error)
                    errorMessage += '<li>- {{ $error }}</li>';
                @endforeach
                errorMessage += '</ul>';

                Swal.fire({
                    title: 'Form Tidak Valid',
                    html: errorMessage,
                    icon: 'error',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                });
            @endif

            // Display success messages
            @if (session('success'))
                Swal.fire({
                    title: 'Berhasil!',
                    text: '{{ session('success') }}',
                    icon: 'success',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            @endif

            // Display info messages
            @if (session('info-wait-verification'))
                Swal.fire({
                    title: 'Menunggu Verifikasi!',
                    text: '{{ session('info-wait-verification') }}',
                    icon: 'info',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            @endif

            // Display info messages
            @if (session('info-cancelled'))
                Swal.fire({
                    title: 'Transaksi Dibatalkan!',
                    text: '{{ session('info-cancelled') }}',
                    icon: 'info',
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'OK'
                });
            @endif

            // Display error messages
            @if (session('error'))
                Swal.fire({
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    icon: 'error',
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'OK'
                });
            @endif
        });
    </script>
</body>

</html>
