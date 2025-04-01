<div class="mx-5 sm:mx-auto {{ $maxWidth ?? 'max-w-max-w-6.5xl' }} mb-10">
    <div class="grid grid-cols-1 sm:grid-cols-4 my-14 ms-5 sm:ms-0 gap-x-10 sm:gap-x-0 lg:gap-x-60">
        <div class="col-span-1 mt-5 w-max">
            <h1 class="font-bold text-lg">COMPANY</h1>
            <ul class="mt-3 text-black-46 font-semibold">
                <li class="flex text-black items-center">
                    <img class="size-10" src="{{ asset('storage/image-removebg.png') }}" alt="My Futsal Logo">
                    MY FUTSAL
                </li>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="#">Carer</a></li>
                <li><a href="#">Blog</a></li>
            </ul>
        </div>
        <div class="col-span-1 mt-5 w-max">
            <h1 class="font-bold text-lg">PRODUCT</h1>
            <ul class="mt-3 text-black-46 font-semibold">
                <li><a href="{{ route('venue') }}">Venue</a></li>
                <li><a href="#">Community</a></li>
            </ul>
        </div>
        <div class="col-span-1 mt-5 w-max">
            <h1 class="font-bold text-lg">SUPPORT</h1>
            <ul class="mt-3 text-black-46 font-semibold">
                <li><a href="#">API Reference</a></li>
                <li><a href="#">Insurance</a></li>
                <li><a href="#">Help Center</a></li>
                <li><a href="#">Live Chat</a></li>
            </ul>
        </div>
        <div class="col-span-1 mt-5 w-max">
            <h1 class="font-bold text-lg mx-auto">Our Logo</h1>
            <img class="-ms-7 lg:-ms-2" src="{{ asset('storage/image-removebg2.png') }}" alt="logo">
        </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-y-5 sm:gap-y-0 mx-5 sm:ms-0 sm:items-center justify-between">
        <div class="flex flex-col">
            <h1 class="font-bold text-lg">PT. YUNTA MANDIRI GROUP</h1>
            <p class="text-black-46 font-semibold mt-3">&copy; 2025 MY FUTSAL All Right Reserved <span class="px-5"><a
                        href="#">Privacy Policy</a></span><span><a href="#">Legal</a></span></p>
        </div>
        <div class="flex flex-col">
            <div class="flex items-center gap-5">
                <a href="#"><img src="{{ asset('storage/social.png') }}" alt="instagram"></a>
                <a href="#"><img src="{{ asset('storage/linkedin.png') }}" alt="linkedin"></a>
                <a href="#"><img src="{{ asset('storage/play.png') }}" alt="play"></a>
                <a href="https://www.facebook.com/hjmuriyahsukses"><img
                        src="{{ asset('storage/facebook-no-color.png') }}" alt="facebook"></a>
            </div>
            <p class="text-black-46 font-semibold mt-3">support@myfutsal<span class="ps-5"><a href="#">+62 812
                        8655 8895</a></span></p>
        </div>
    </div>
</div>
