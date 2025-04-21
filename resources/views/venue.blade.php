@extends('layouts.app')

@section('header')
    @include('components.navbar')
    <img class="fixed top-16 z-50 shadow-lg w-full" src="{{ asset('storage/stick.png') }}" alt="stick" class="w-full">
@endsection

@section('content')
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-10 mt-28">
        <h1 class="font-bold mt-12 mb-8 text-2xl justify-center flex">BOOKING <span class="text-[#E72929] px-2">VENUE</span></h1>
        <div class="bg-[#252525] mt-10 mb-10 rounded-xl py-6 px-4">
            <form action="{{ route('venue') }}" method="get">
                <div
                    class="mx-auto max-w-6xl flex flex-col md:flex-row md:items-center md:space-x-5 space-y-4 md:space-y-0">
                    <div class="flex-1">
                        <input type="text" name="q" placeholder="Cari nama venue..." value="{{ request('q') }}"
                            class="w-full bg-white rounded-xl py-2 px-4 font-semibold shadow">
                    </div>

                    <button type="submit"
                        class="bg-my-red shadow-xl font-bold w-full md:w-auto rounded-xl text-white py-2 px-6">Search</button>
                </div>
            </form>
        </div>

        @if (request('city') || request('sport'))
            <div class="mb-8 bg-white rounded-xl px-6 py-4 shadow">
                <h3 class="text-lg font-bold mb-2">Filter Saat Ini:</h3>
                <ul class="list-disc list-inside text-gray-700 space-y-1">
                    @if (request('city'))
                        <li>Kota: <span class="font-semibold text-[#E72929]">{{ request('city') }}</span></li>
                    @endif
                    @if (request('sport'))
                        <li>Olahraga: <span class="font-semibold text-[#E72929]">{{ request('sport') }}</span></li>
                    @endif
                    @if (request('q'))
                        <li>Venue: <span class="font-semibold text-[#E72929]">{{ request('q') }}</span></li>
                    @endif
                </ul>
                <a href="{{ route('venue') }}"
                    class="inline-block mt-4 text-sm text-my-red font-semibold hover:underline">Reset Filter</a>
            </div>
        @endif
        @if ($venues->isEmpty())
            <p class="text-center text-gray-500 col-span-full">Tidak ada venue yang cocok dengan filter yang dipilih.</p>
        @endif

        <h2 class="font-bold mt-12 mb-8 text-xl">BARU <span class="text-[#E72929]">DITAMBAHKAN</span></h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 2xl:grid-cols-4 gap-10 2xl:gap-7">
            @foreach ($venues as $venue)
                <div class="flex flex-col items-center">
                    <a href="/v/{{ $venue->slug }}">
                        <img src="{{ asset('storage/' . $venue->image) }}" alt="{{ $venue->name }}"
                            class="w-[384px] h-[292px] object-cover rounded-2xl">
                        <div class="ms-2 mt-5">
                            <h3 class="font-bold mb-1 text-lg">{{ $venue->name }}</h3>
                            <p class="font-semibold mb-1">{{ $venue->city }}</p>
                            <p class="font-semibold">Harga Mulai <span class="text-green-400">Rp. 80.000</span></p>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
        <div class="mt-8">
            {{ $venues->links() }}
        </div>
    </div>
@endsection

@section('footer')
    @include('components.footer')
@endsection
