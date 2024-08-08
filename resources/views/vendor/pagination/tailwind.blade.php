@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">
        <div class="flex-1 flex justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="text-gray-500 dark:text-gray-400 cursor-not-allowed">{{ __('Previous') }}</span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">{{ __('Previous') }}</a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">{{ __('Next') }}</a>
            @else
                <span class="text-gray-500 dark:text-gray-400 cursor-not-allowed">{{ __('Next') }}</span>
            @endif
        </div>

        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700 dark:text-gray-300">
                    {{ __('Showing') }}
                    <span class="font-medium">{{ $paginator->firstItem() }}</span>
                    {{ __('to') }}
                    <span class="font-medium">{{ $paginator->lastItem() }}</span>
                    {{ __('of') }}
                    <span class="font-medium">{{ $paginator->total() }}</span>
                    {{ __('results') }}
                </p>
            </div>

            <div>
                <ul class="inline-flex space-x-1">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li>
                            <span class="relative inline-flex items-center px-4 py-2 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                                <span class="sr-only">{{ __('Previous') }}</span>
                                &lsaquo;
                            </span>
                        </li>
                    @else
                        <li>
                            <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                <span class="sr-only">{{ __('Previous') }}</span>
                                &lsaquo;
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li>
                                <span class="relative inline-flex items-center px-4 py-2 text-gray-500 dark:text-gray-400 cursor-not-allowed">{{ $element }}</span>
                            </li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li>
                                        <span class="relative inline-flex items-center px-4 py-2 text-indigo-600 dark:text-indigo-400 bg-indigo-200 dark:bg-indigo-800 border border-indigo-300 dark:border-indigo-700">{{ $page }}</span>
                                    </li>
                                @else
                                    <li>
                                        <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li>
                            <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center px-4 py-2 text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300">
                                <span class="sr-only">{{ __('Next') }}</span>
                                &rsaquo;
                            </a>
                        </li>
                    @else
                        <li>
                            <span class="relative inline-flex items-center px-4 py-2 text-gray-500 dark:text-gray-400 cursor-not-allowed">
                                <span class="sr-only">{{ __('Next') }}</span>
                                &rsaquo;
                            </span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
@endif
