@extends('layouts.app')

@section('header')
    @include('components.navbar')
    <img class="fixed top-16 z-10 shadow-lg w-full" src="{{ asset('storage/stick.png') }}" alt="stick" class="w-full">
@endsection

@section('content')
    @include('components.hero')
    <div class="mx-5 sm:mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <h2 class="font-bold mt-12 mb-8 text-xl">VENUE <span class="text-[#E72929]">HJ. MURIYAH</span></h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 2xl:grid-cols-4 gap-10 2xl:gap-7">
            @foreach ($venues as $venue)
                <div class="flex flex-col">
                    <a href="/v/{{ $venue->slug }}">
                        <img class="w-[384px] h-[292px] object-cover rounded-2xl"
                            src="{{ asset('storage/' . $venue->image) }}" alt="{{ $venue->name }}">
                        <div class="ms-2 mt-5">
                            <h3 class="font-bold mb-1 text-lg">{{ $venue->name }}</h3>
                            <p class="font-semibold mb-1">{{ $venue->city }}</p>
                            <p class="font-semibold">Harga Mulai <span class="text-green-400">Rp
                                    80.000</span></p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        @include('components.banner')
    </div>
@endsection

@section('footer')
    @include('components.footer')
@endsection
