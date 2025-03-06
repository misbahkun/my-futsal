<nav class="bg-white fixed top-0 z-50 w-full">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">
        <div class="flex h-16 items-center justify-between" x-data="{ menu: $store.menu }">
            <div class="flex items-center">
                <img class="size-10" src="{{ asset('storage/image-removebg.png') }}" alt="My Futsal Logo">
                <span class="font-bold"><a href="{{ route('home') }}">My Futsal</a></span>
            </div>
            <div class="flex md:hidden gap-x-5">
                <div class="relative cursor-pointer cart">
                    <img class="w-10" src="{{ asset('storage/shopping-cart.png') }}" alt="cart">
                    <span class="absolute -top-3 right-0 font-semibold text-my-red"
                        x-text="$store.cart.getCartCount()"></span>
                </div>
                <img class="cursor-pointer" src="{{ asset('storage/menu.png') }}" @click="$store.menu.handleOpen()"
                    alt="Menu">
            </div>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6 font-bold space-x-6">
                    <a href="{{ route('home') }}"><span class="fw-bold">HOME</span></a>
                    <a href="{{ route('venue') }}"><span class="fw-bold">VENUE</span></a>
                    <div class="relative cursor-pointer cart">
                        <img class="w-10" src="{{ asset('storage/shopping-cart.png') }}" alt="cart">
                        <span class="absolute -top-3 right-0 font-semibold text-my-red"
                            x-text="$store.cart.getCartCount()"></span>
                    </div>
                    @if (Auth::check())
                        <div class="relative flex gap-1" @click="menu.handleAuthClick()">
                            <p class="fw-semibold cursor-pointer">{{ Auth::user()->full_name }}</p>
                            <img :src="menu.auth ? '{{ asset('storage/up-arrows.png') }}' :
                                '{{ asset('storage/down-arrows.png') }}'"
                                class="w-5 h-5 cursor-pointer" alt="chevron">
                            <div x-show="menu.auth === true"
                                class="space-y-3 flex flex-col absolute top-10 bg-white p-5 rounded-lg shadow-xl">
                                <a class="font-semibold cursor-pointer" href="/dashboard">Dashboard</a>
                                <div class="flex gap-1 cursor-pointer">
                                    <form action="{{ route('logout') }}" method="POST" class="cursor-pointer">
                                        @csrf
                                        <button type="submit" class="flex gap-2">
                                            <span>Logout</span>
                                            <img src="{{ asset('storage/logout.png') }}" class="w-5 h-5"
                                                alt="logout">
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <button class="shadow-grey ms-2 rounded-xl py-2 px-3"><a
                                href="{{ route('register') }}">REGISTER</a></button>
                        <button class="bg-my-red shadow-xl ms-2 rounded-xl text-white py-2 px-5"><a
                                href="{{ route('login') }}">LOGIN</a></button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    </div>
    <div x-data="{ menu: $store.menu }">
        <div x-show="menu.open === true">
            <div class="flex flex-col items-center font-bold space-y-5 md:hidden shadow-lg pb-4 rounded-lg">
                <a href="{{ route('home') }}"><span class="fw-bold">HOME</span></a>
                <a href="{{ route('venue') }}"><span class="fw-bold">VENUE</span></a>
                @if (Auth::check())
                    <div class="flex cursor-pointer gap-1" @click="menu.handleAuthClick()">
                        <p class="fw-semibold">{{ Auth::user()->full_name }}</p>
                        <img :src="menu.auth ? '{{ asset('storage/up-arrows.png') }}' : '{{ asset('storage/down-arrows.png') }}'"
                            class="w-5 h-5" alt="chevron">
                    </div>
                    <div x-show="menu.auth === true" class="space-y-3 flex flex-col">
                        <a class="font-semibold cursor-pointer" href="/dashboard">Dashboard</a>
                        <div class="flex gap-1">
                            <form action="{{ route('logout') }}" method="POST" class="cursor-pointer">
                                @csrf
                                <button type="submit" class="flex gap-2">
                                    <span>Logout</span>
                                    <img src="{{ asset('storage/logout.png') }}" class="w-5 h-5" alt="logout">
                                </button>
                            </form>
                        </div>
                    </div>
                @else
                    <button class="shadow-grey ms-2 rounded-xl py-2 px-3 w-96"><a
                            href="{{ route('register') }}">REGISTER</a></button>
                    <button class="bg-my-red shadow-xl ms-2 rounded-xl text-white py-2 px-5 w-96"><a
                            href="{{ route('login') }}">LOGIN</a></button>
                @endif
            </div>
        </div>
    </div>
</nav>

<div class="fixed inset-0 z-50 bg-black bg-opacity-50 hidden justify-end items-center sidebar" x-data="{ cart: $store.cart, open: false }">
    <div class="bg-white rounded-2xl h-full p-5 w-4/5 md:w-2/3 lg:w-1/2  overflow-y-auto">
        <div class="flex justify-between">
            <h1 class="font-bold text-2xl mb-5">Cart</h1>
            <img src="{{ asset('storage/close.png') }}" id="closeSidebar" class="w-8 h-8 cursor-pointer"
                alt="close button">
        </div>

        <form id="bookForm" action="{{ route('book') }}" method="POST">
            @csrf
            <template x-for="(item, index) in cart.items" :key="index">
                <div class="mb-4 border border-gray-500 rounded-lg p-4">
                    <div class="flex justify-between" x-data="{ cart: $store.cart }">
                        <h2 x-text="item.field" class="font-semibold text-xl"></h2>
                        <img src="{{ asset('storage/close.png') }}" @click="cart.removeFromCart(index)"
                            class="w-8 h-8 cursor-pointer" alt="close button">
                    </div>
                    <p x-text="item.venue"></p>
                    <p x-text="item.date"></p>
                    <p x-text="cart.formatPrice(item.price)"></p>
                    <input type="hidden" name="items[]" :value="JSON.stringify(item)">
                </div>
            </template>
            <button type="submit"
                class="w-full bg-my-red font-bold text-xl text-white py-2 rounded-lg hover:bg-red-800 cursor-pointer mt-3">
                Lanjutkan Pembayaran
            </button>
        </form>
    </div>
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cart = document.getElementsByClassName('cart');

            for (let i = 0; i < cart.length; i++) {
                cart[i].addEventListener('click', function() {
                    document.querySelector('.sidebar').classList.remove('hidden');
                    document.querySelector('.sidebar').classList.add('flex');
                });
            }

            window.addEventListener('click', function(e) {
                if (e.target.classList.contains('sidebar')) {
                    document.querySelector('.sidebar').classList.remove('flex');
                    document.querySelector('.sidebar').classList.add('hidden');
                }
            });

            document.getElementById('closeSidebar').addEventListener('click', function() {
                document.querySelector('.sidebar').classList.remove('flex');
                document.querySelector('.sidebar').classList.add('hidden');
            })

            document.getElementById('bookForm').addEventListener('submit', function(e) {
                const cart = JSON.parse(localStorage.getItem('cart'))

                if (cart.length == 0)  {
                    e.preventDefault()
                    alert('cart masih kosong')
                    return
                }

                @if (!Auth::check())
                    e.preventDefault();
                    alert('Silakan login terlebih dahulu untuk melanjutkan pembayaran.');
                    window.location.href = "{{ route('login') }}";
                @endif

                localStorage.setItem('cart', JSON.stringify([]));
            });
        });
    </script>
@endpush
