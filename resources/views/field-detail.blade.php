@extends('layouts.app')

@section('title')
    Booking Lapangan {{ $field->name }} - {{ $field->venue->name }}
@endsection

@section('header')
    @include('components.navbar')
    <img class="fixed top-16 z-10 shadow-lg w-full" src="{{ asset('storage/stick.png') }}" alt="stick" class="w-full">
@endsection

@section('content')
    <div class="mx-5 mt-24 sm:mx-auto sm:max-w-6xl sm:mt-28 " x-data="{ active: 'Schedule' }">
        <div class="relative">
            <img src="{{ asset('storage/' . $field->image) }}" alt="{{ $field->venue->large_image }}"
                class="object-cover h-[500px] w-full rounded-2xl">
            @if ($field->venue->logo_image !== null)
                <img src="{{ asset('storage/' . $field->venue->logo_image) }}"
                    class="absolute w-12 bottom-3 left-10 m-4 lg:w-24" alt="{{ $field->venue->logo_image }}">
            @endif
        </div>
        <h1 class="font-bold text-2xl mt-5 mb-1">{{ $field->name }}</h1>
        <a href="{{ route('venue.detail', ['slug' => $field->venue->slug]) }}"
            class="underline text-my-red font-bold">{{ $field->venue->name }}</a>

        {{-- Komponen share --}}
        @include('components.share')

        <ul class="flex justify-center items-center my-16 gap-x-20 font-bold border-t border-b w-full">
            <li :class="{ 'bg-my-red text-white p-3 rounded-lg': active === 'Schedule' }" @click="active = 'Schedule'"
                class="cursor-pointer">Schedule</li>
            <li :class="{ 'bg-my-red text-white p-3 rounded-lg': active === 'Rules' }" @click="active = 'Rules'"
                class="cursor-pointer">Rules</li>
        </ul>

        <div x-show="active === 'Schedule'" x-data="{
            date: '{{ date('Y-m-d') }}',
            status: {{ json_encode($status) }},
            prices: {{ json_encode($prices) }},
            originalPrices: {{ json_encode($originalPrices ?? []) }},
            timePrices: {{ json_encode($timePrices) }},
            hasDiscount: {{ json_encode($hasDiscount ?? false) }},
            discount: {{ json_encode($discount ?? 0) }},
            calculateTimePrice(timeSlot) {
                const startTime = timeSlot.split(' - ')[0];
                const startHour = parseInt(timeSlot.split(':')[0]);
                return (startHour >= 8 && startHour < 16) ? this.timePrices.pagi : this.timePrices.sore;
            },
            calculateOriginalTimePrice(timeSlot) {
                if (!this.hasDiscount) {
                    return this.calculateTimePrice(timeSlot);
                }
                // Calculate original price before discount
                const startHour = parseInt(timeSlot.split(':')[0]);
                const price = (startHour >= 8 && startHour < 16) ? this.timePrices.pagi : this.timePrices.sore;
                return Math.round(price / (1 - this.discount));
            },
            getSlotStatus(date, timeSlot) {
                // Periksa apakah status tersedia di data dari server
                if (this.status[date + ' ' + timeSlot]) {
                    return this.status[date + ' ' + timeSlot];
                }
                // Default status jika tidak ada di server adalah 'available'
                return 'available';
            },
            formatPrice(price) {
                return 'Rp ' + price.toLocaleString('id-ID', { minimumFractionDigits: 0, maximumFractionDigits: 0 });
            },
            openDatePicker() {
                this.$refs.dateInput.showPicker();
            },
            formatDate(date, format = 'default') {
                if (!date) return 'Pilih Tanggal';
                const [year, month, day] = date.split('-');
                const months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                const dateObj = new Date(year, month - 1, day);
                if (format === 'schedule') {
                    return `${day} ${months[parseInt(month) - 1]}`;
                }
                if (format === 'day') {
                    return days[dateObj.getDay()];
                }
                return `${day}-${month}-${year}`;
            },
            getNextDates(date, count) {
                const dates = [];
                const [year, month, day] = date.split('-');
                const startDate = new Date(year, month - 1, day);
                for (let i = 1; i <= count; i++) {
                    const nextDate = new Date(startDate);
                    nextDate.setDate(startDate.getDate() + i);
                    const formattedDate = nextDate.toISOString().split('T')[0];
                    dates.push(formattedDate);
                }
                return dates;
            },
            timeSlots: [
                '23:00 - 00:00',
                '22:00 - 23:00',
                '21:00 - 22:00',
                '20:00 - 21:00',
                '19:00 - 20:00',
                '18:00 - 19:00',
                '17:00 - 18:00',
                '16:00 - 17:00',
                '15:00 - 16:00',
                '14:00 - 15:00',
                '13:00 - 14:00',
                '12:00 - 13:00',
                '11:00 - 12:00',
                '10:00 - 11:00',
                '09:00 - 10:00',
                '08:00 - 09:00'
            ],
        }">
            <div class="grid grid-cols-1 gap-10 sm:grid-cols-2 sm:gap-32 2xl:gap-52">
                <div>
                    <h1 class="mb-5 font-bold text-lg">Pilih Tanggal Booking :</h1>
                    <div class="relative flex items-center justify-between space-x-2 bg-white rounded-xl px-4 cursor-pointer shadow-lg outline"
                        @click="openDatePicker()">
                        <span class="font-bold mx-auto" x-text="formatDate(date)"></span>
                        <img src="{{ asset('storage/calendar.png') }}" alt="calendar">
                        <input type="date" x-ref="dateInput" x-model="date"
                            class="absolute inset-0 w-full h-full opacity-0 pointer-events-none">
                    </div>
                    <div class="mt-8 text-sm">
                        <p>*Periode <span class="text-my-red">Tanggal Booking</span> :</p>
                        <p>*Tap pada kolom waktu untuk memilih <span class="text-my-red">Jam Booking</span></p>
                        <template x-if="hasDiscount">
                            <p class="mt-2 font-semibold text-green-600">*Anda mendapatkan diskon 10% sebagai member</p>
                        </template>
                    </div>
                </div>
                <div>
                    <h1 class="mb-5 font-bold text-lg">Pilih Lapangan :</h1>
                    <div x-data="{ selectedField: '{{ $field->name }}', selectedFieldId: '{{ $field->id }}' }"
                        class="relative flex items-center justify-between py-1.5 space-x-2 bg-white rounded-xl px-4 cursor-pointer shadow-lg outline">
                        <span class="font-bold mx-auto" x-text="selectedField"></span>
                        <img src="{{ asset('storage/chevron-down.png') }}" alt="chevron">
                        <select x-model="selectedFieldId"
                            @change="
                                selectedField = Array.from($event.target.selectedOptions).find(option => option.value == selectedFieldId).text;
                                window.location.href = '{{ route('field.detail', '') }}/' + selectedFieldId;
                            "
                            class="absolute inset-0 opacity-0 cursor-pointer">
                            @foreach ($fields as $f)
                                <option value="{{ $f->id }}" {{ $f->id === $field->id ? 'selected' : '' }}>
                                    {{ $f->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <div class="grid grid-cols-6 gap-4 mt-8 min-w-max">
                    <template x-for="nextDate in getNextDates(date, 6)">
                        <div class="border border-black rounded-lg bg-[#FFF6F6] py-6 px-7 sm:px-9.9 flex flex-col">
                            <p class="font-bold text-lg text-center" x-text="formatDate(nextDate, 'schedule')"></p>
                            <p class="text-sm text-my-red font-semibold text-center" x-text="formatDate(nextDate, 'day')">
                            </p>
                        </div>
                    </template>
                </div>
                <div class="grid grid-cols-6 gap-4 mt-8 min-w-max">
                    <template x-for="timeSlot in timeSlots">
                        <template x-for="nextDate in getNextDates(date, 6)">
                            <div @click="$store.cart.addToCart({
                                field_id: Number('{{ $field->id }}'),
                                venue: '{{ $field->venue->name }}',
                                field: '{{ $field->name }}',
                                date: `${nextDate} ${timeSlot}`,
                                price: calculateTimePrice(timeSlot),
                                status: status[nextDate + ' ' + timeSlot]
                            })"
                                class="border border-black rounded-lg p-4 flex flex-col"
                                :class="status[nextDate + ' ' + timeSlot] === 'booked' ? 'bg-[#D1BBBB] cursor-default' :
                                    $store.cart.isInCart(`${nextDate} ${timeSlot}`, '{{ $field->name }}') ?
                                    'bg-green-500 cursor-pointer text-white' : 'bg-[#FFF6F6] cursor-pointer'">
                                <p class="text-sm font-semibold text-end mb-3" x-text="formatDate(nextDate, 'schedule')">
                                </p>
                                <p class="text-md font-semibold text-center" x-text="timeSlot"></p>
                                <template x-if="hasDiscount">
                                    <div class="text-center">
                                        <p class="text-lg font-semibold line-through text-gray-600"
                                            x-text="formatPrice(originalPrices[nextDate + ' ' + timeSlot] || 
                                                Math.round(calculateTimePrice(timeSlot) / (1 - discount)))">
                                        </p>
                                        <p class="text-lg font-semibold text-green-600"
                                            :class="$store.cart.isInCart(`${nextDate} ${timeSlot}`, '{{ $field->name }}') ?
                                                'text-white' : 'text-green-600'"
                                            x-text="formatPrice(prices[nextDate + ' ' + timeSlot] || calculateTimePrice(timeSlot))">
                                        </p>
                                    </div>
                                </template>
                                <template x-if="!hasDiscount">
                                    <p class="text-lg font-semibold text-center"
                                        x-text="formatPrice(prices[nextDate + ' ' + timeSlot] || calculateTimePrice(timeSlot))">
                                    </p>
                                </template>
                                <p class="text-sm font-semibold text-center"
                                    :class="getSlotStatus(nextDate, timeSlot) === 'booked' ? 'text-black' : $store.cart
                                        .isInCart(`${nextDate} ${timeSlot}`, '{{ $field->name }}') ? 'text-white' :
                                        'text-my-red'"
                                    x-text="getSlotStatus(nextDate, timeSlot) === 'booked' ? 'Booked' : 'Available'">
                                </p>
                            </div>
                        </template>
                    </template>
                </div>
            </div>
        </div>

        <div x-show="active === 'Rules'">
            <div class="grid grid-cols-1 sm:grid-cols-2 sm:gap-y-5 gap-10 2xl:gap-7">
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
    @endsection

    @section('footer')
        @include('components.footer', ['maxWidth' => 'max-w-6xl'])
    @endsection