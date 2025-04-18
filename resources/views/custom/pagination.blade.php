@if ($paginator->hasPages())
    <nav>
        <ul class="pagination flex justify-between items-center mt-7 font-bold">
            @if ($paginator->onFirstPage())
                <li class="disabled" aria-disabled="true">
                    <span class="dark:text-gray-400">Previous</span>
                </li>
            @else
                <li>
                    <a href="{{ $paginator->previousPageUrl() }}" class="dark:text-white">Previous</a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li>
                    <a href="{{ $paginator->nextPageUrl() }}" class="dark:text-white">Next</a>
                </li>
            @else
                <li class="disabled" aria-disabled="true">
                    <span class="dark:text-gray-400">Next</span>
                </li>
            @endif
        </ul>
    </nav>
@endif
