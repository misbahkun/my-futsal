<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>
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
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.store('cart', {
                items: JSON.parse(localStorage.getItem('cart')) || [],

                addToCart(data) {
                    if (this.items.some(item => item.date === data.date && item.field === data.field)) {
                        this.items = this.items.filter(item => item.date !== data.date || item.field !==
                            data.field);
                    } else {
                        if (this.items.length >= 4) {
                            alert('Maksimal hanya dapat memilih 4 slot dalam 1 transaksi.');
                            return;
                        }
                        this.items.push(data);
                    }
                    localStorage.setItem('cart', JSON.stringify(this.items));
                },

                getCartCount() {
                    return this.items.length;
                },

                isInCart(date, field) {
                    return this.items.some(item => item.date === date && item.field === field);
                }
            });
        });
    </script>
</body>

</html>
