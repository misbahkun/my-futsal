@extends('layouts.app')

@section('title', 'Detail')

@section('header')
    @include('components.navbar')
    <img class="fixed top-16 z-10 shadow-lg w-full" src="{{ asset('storage/stick.png') }}" alt="stick" class="w-full">
@endsection

@section('content')
    <div class="mx-5 mt-24 sm:mx-auto max-w-6xl sm:mt-28" x-data="{ active: 'Lapangan' }">
        <div class="relative">
            <img src="{{ asset('storage/' . $venue->large_image) }}" alt="{{ $venue->large_image }}" class="rounded-2xl">
        </div>
        <h1 class="font-bold text-2xl mt-5">{{ $venue->name }}</h1>
        <ul class="flex gap-x-2 mt-1">
            @foreach ($venue->sports as $sport)
                <li>
                    <a href="#" class="underline text-my-red font-bold">{{ $sport->name }}</a>
                    @if (!$loop->last)
                        <span class="text-black font-semibold">,</span>
                    @endif
                </li>
            @endforeach
            <li class="font-semibold">in <a href="#"
                    class="cursor-pointer underline text-my-red font-bold">{{ $venue->city }}</a></li>
        </ul>
        {{-- Komponen share --}}
        @include('components.share')

        <ul
            class="overflow-x-auto flex justify-center items-center my-16 gap-x-5 sm:gap-x-40 font-bold border-t border-b w-full">
            <li :class="{ 'bg-my-red text-white p-3 rounded-lg': active === 'Lapangan' }" @click="active = 'Lapangan'"
                class="cursor-pointer">Lapangan</li>
            <li :class="{ 'bg-my-red text-white p-3 rounded-lg': active === 'About' }" @click="active = 'About'"
                class="cursor-pointer">About</li>
            <li :class="{ 'bg-my-red text-white p-3 rounded-lg': active === 'Rules' }" @click="active = 'Rules'"
                class="cursor-pointer">Rules</li>
            <li :class="{ 'bg-my-red text-white p-3 rounded-lg': active === 'Gallery' }" @click="active = 'Gallery'"
                class="cursor-pointer">Gallery</li>
        </ul>

        <div x-show="active === 'Lapangan'">
            <h2 class="font-bold text-2xl my-5">Field</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 sm:gap-y-5 gap-10 2xl:grid-cols-3 2xl:gap-x-14 2xl:gap-y-6">
                @foreach ($venue->fields as $index => $field)
                    <div class="flex flex-col {{ $index % 2 == 1 ? 'ms-auto' : 'me-auto' }} mb-5 lg:mb-14">
                        <div class="flex flex-col items-center gap-y-4">
                            <img src="{{ asset('storage/' . $field->image) }}" class="w-[500px] h-[415px] rounded-2xl"
                                alt="{{ $field->name }}">
                            <h3 class="font-bold mb-1 text-lg">{{ $field->name }}</h3>
                            <p class="font-semibold">
                                Rp 80.000 ~ Rp
                                150.000
                            </p>
                            <a href="{{ route('field.detail', ['id' => $field->id]) }}">
                                <button
                                    class="bg-my-red shadow-xl font-bold ms-2 w-[18.75rem] rounded-xl text-white py-3 px-5">Lihat
                                    Jadwal</button>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div x-show="active === 'About'">
            <div class="grid grid-cols-1 sm:grid-cols-2 sm:gap-y-5 gap-10 2xl:gap-y-9 2xl:gap-x-10">
                <div class="bg-my-grey rounded-xl py-6 px-10">
                    <p class="text-my-red font-bold text-lg">Deskripsi :</p>
                    <p class="text-my-red font-semibold mt-2">Asatu Area</p>
                    <ul class="list-disc list-inside">
                        <li>Tenants F&B Area</li>
                        <li>Gym</li>
                        <li>Mini Soccer</li>
                    </ul>
                    <p class="text-my-red font-semibold mt-2">Fasilitas</p>
                    <ul class="list-disc list-inside">
                        <li>Shower Room</li>
                        <li>Parkiran +40 Mobil 20+ Motor</li>
                    </ul>
                </div>
                <div class="bg-my-grey rounded-xl py-6 px-10">
                    <p class="text-my-red font-bold mb-3 text-lg">Jam Operasional :</p>
                    <ul class="space-y-2">
                        <li class="flex justify-between">
                            <span class="font-bold">Hari</span>
                            <span class="font-bold me-9">Jam</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Senin</span>
                            <span>06.00 - 00.00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Selasa</span>
                            <span>06.00 - 00.00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Rabu</span>
                            <span>06.00 - 00.00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Kamis</span>
                            <span>06.00 - 00.00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Jumat</span>
                            <span>06.00 - 00.00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Sabtu</span>
                            <span>06.00 - 00.00</span>
                        </li>
                        <li class="flex justify-between">
                            <span>Minggu</span>
                            <span>06.00 - 00.00</span>
                        </li>
                    </ul>
                </div>
                <div class="bg-my-grey rounded-xl py-6 px-10">
                    <p class="text-my-red font-bold text-lg">Fasilitas</p>
                    <div class="grid grid-cols-2 sm:grid-cols-3 2xl:grid-cols-6 gap-10 2xl:gap-7 mt-5">
                        <div class="flex flex-col items-center">
                            <img src="{{ asset('storage/parking.png') }}" alt="">
                            <span>Parkir</span>
                        </div>
                        <div class="flex flex-col items-center">
                            <img src="{{ asset('storage/toilet.png') }}" alt="">
                            <span>Toilet</span>
                        </div>
                    </div>
                </div>
                <div class="bg-my-grey rounded-xl py-6 px-10">
                    <p class="text-my-red font-bold text-lg">Informasi</p>
                    <div class="flex items-center mt-5 gap-4">
                        <a href="https://www.facebook.com/hjmuriyahsukses"><img src="{{ asset('storage/facebook.png') }}"
                                class="ms-1 w-9 h-9" alt=""></a>
                        <a href="https://www.facebook.com/hjmuriyahsukses">Facebook</a>
                    </div>
                    <div class="flex items-center mt-5 gap-4">
                        <a href="https://maps.app.goo.gl/PHv9uSt5c9BoA8sn6?g_st=aw"><img
                                src="{{ asset('storage/location.png') }}" alt=""></a>
                        <a href="https://maps.app.goo.gl/PHv9uSt5c9BoA8sn6?g_st=aw">Jl. Jenderal Ahmad Yani No.1, Procot,
                            Kec. Slawi, Kabupaten Tegal, Jawa Tengah 52412</a>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="active === 'Rules'">
            <div class="grid grid-cols-1 sm:grid-cols-2 sm:gap-y-5 gap-10 2xl:gap-y-9 2xl:gap-x-10">
                <div class="bg-my-grey rounded-xl py-6 px-10">
                    <p class="text-my-red font-bold text-lg mb-3">Aturan Venue :</p>
                    <p>- Pemesanan lapangan valid apabila sudah ada konfirmasi dari online booking system, dan harus
                        ditunjukkan kepada staf Inspire Arena.</p>
                    <p>- Pengguna lapangan yang walk-in tetap melakukan pemesanan dan pembayaran via sistem sebelum memasuki
                        lapangan.</p>
                    <p>- TIDAK ADA perpanjangan waktu untuk keterlambatan pengguna lapangan di jam reservasi.</p>
                    <p>- TIDAK ADA kebijakan Refund dari pihak pengelola; Reschedule bisa dilakukan by request melalui
                        sistem selambatnya H-5.</p>
                    <p>- Peminjaman bola bisa dilakukan dengan menitipkan KTP atau SIM ke staf Inspire Arena.</p>
                    <p>- Kunci kendaraan Anda di parkiran, dan tunjukkan karcis parkir ketika meninggalkan Inspire Arena.
                    </p>
                    <p>- Barang bawaan menjadi tanggung jawab masing-masing; segala bentuk kerusakan dan kehilangan di luar
                        tanggung jawab Inspire Arena.</p>
                    <p>- Jagalah kebersihan di seluruh area Inspire Arena dan buanglah sampah ke tempat sampah yang telah
                        disediakan.</p>
                    <p>- DILARANG merokok di semua area lapangan, foodcourt dll; merokok hanya diperbolehkan di area yang
                        disediakan di taman belakang.</p>
                    <p>- DILARANG membawa air mineral kemasan sekali pakai, kami menyediakan air minum isi ulang secara
                        gratis untuk semua pengunjung.</p>
                    <p>- DILARANG makan dan minum di dalam lapangan.</p>
                    <p>- DILARANG membawa senjata tajam, minuman keras / alkohol maupun obat-obatan terlarang.</p>
                </div>
                <div class="bg-my-grey rounded-xl py-6 px-10">
                    <p class="text-my-red font-bold text-lg mb-4 text-center">Protokol Kesehatan</p>
                    <div class="flex mb-5">
                        <div class="flex flex-col flex-1">
                            <p class="text-my-red font-semibold">Batasan Jumlah Pemain :</p>
                            <p>Jumlah pemain yang memasuki area lapangan dibatasi, dianjurkan tidak membawa keluarga atau
                                teman.</p>
                        </div>
                        <div class="flex flex-col flex-1">
                            <p class="text-my-red font-semibold">Suhu Tubuh :</p>
                            <p>Wajib dalam keadaan sehat, suhu badan diatas 37.3℃  tidak di izinkan untuk memasuki area
                                lapangan.
                            <p>
                        </div>
                    </div>
                    <div class="flex">
                        <div class="flex flex-col flex-1">
                            <p class="text-my-red font-semibold">Masker dan Cuci Tangan :</p>
                            <p>Wajib menggunakan <span class="hidden sm:inline"><br></span>
                                masker, dan mencuci <span class="hidden sm:inline"><br></span> tangan sebelum dan <span
                                    class="hidden sm:inline"><br></span> sesudah bermain.</p>
                        </div>
                        <div class="flex flex-col flex-1">
                            <p class="text-my-red font-semibold">Suhu Tubuh :</p>
                            <p>Wajib dalam keadaan sehat, suhu badan diatas 37.3℃  tidak di izinkan untuk memasuki area
                                lapangan.
                            <p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="active === 'Gallery'">
            <div class="grid grid-cols-1 sm:grid-cols-3 sm:gap-y-5 2xl:grid-cols-4 gap-10 2xl:gap-7">
                @foreach ($venue->galleries as $gallery)
                    <div class="flex flex-col flex-1 items-center">
                        <img class="h-[300px] w-[342px] object-cover rounded-lg"
                            src="{{ asset('storage/' . $gallery->image) }}" alt="{{ $venue->name }}">
                    </div>
                @endforeach
            </div>
        </div>
    @endsection

    @section('footer')
        @include('components.footer')
    @endsection
