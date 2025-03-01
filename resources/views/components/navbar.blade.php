<nav class="bg-white fixed top-0 z-50 w-full">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">
        <!-- x-data ini untuk agar bisa akses ke state global cart -->
        <div class="flex h-16 items-center justify-between" x-data>
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
                        <a href="#"><span class="fw-bold">{{ Auth::user()->full_name }}</span></a>
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
                            <form action="" method="POST">
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
