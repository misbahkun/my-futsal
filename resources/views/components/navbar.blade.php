<nav class="bg-white fixed top-0 z-50 w-full">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10">
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
                <img class="cursor-pointer" src="{{ asset('storage/menu.png') }}" @click="open = true" alt="Menu">
            </div>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center md:ml-6 font-bold space-x-6">
                    <a href="{{ route('home') }}"><span class="fw-bold">HOME</span></a>
                    <a href="{{ route('venue') }}"><span class="fw-bold">VENUE</span></a>
                    <div class="relative cursor-pointer cart">
                        <img class="w-10" src="{{ asset('storage/shopping-cart.png') }}" alt="cart">
                        <span class="absolute -top-3 right-0 font-semibold text-my-red" x-text="$store.cart.getCartCount()"></span>
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
</nav>