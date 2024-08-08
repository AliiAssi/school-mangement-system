<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Subject Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Subject Details -->
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Name') }}</h3>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">{{ $subject->name }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Abbreviation') }}</h3>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">{{ $subject->abbreviation }}</p>
                    </div>

                    <div class="mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">{{ __('Description') }}</h3>
                        <p class="mt-1 text-gray-600 dark:text-gray-400">{{ $subject->description }}</p>
                    </div>

                    <!-- Actions -->
                    <div class="flex space-x-4 mt-6">
                        <a href="{{ route('subjects.edit', $subject->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:outline-none focus:ring focus:ring-yellow-300 transition dark:bg-yellow-600 dark:hover:bg-yellow-700 dark:focus:ring-yellow-500">
                            {{ __('Edit') }}
                        </a>

                        <form action="{{ route('subjects.destroy', $subject->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this subject?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring focus:ring-red-300 transition dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-500">
                                {{ __('Delete') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
