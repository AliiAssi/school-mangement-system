<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Auto Generation Time Tables Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Display any success message -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md shadow-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Display validation errors -->
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-md shadow-md">
                            <ul class="list-disc pl-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-6">
                        <a href="{{ route('settings.edit', ['setting' => 'dummy-id']) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 transition">
                            {{ __('Edit Settings') }}
                        </a>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-900 p-6 rounded-md shadow-md">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ __('School Schedule') }}</h3>
                        <ul class="list-disc pl-5 space-y-4">
                            <li><strong class="text-red-300">{{ __('Start Time of the School') }}:</strong> <span class="text-red-600">{{ $settings['start_time'] }}</span></li>
                            <li><strong class="text-red-300">{{ __('End Time of the School') }}:</strong> <span class="text-red-600">{{ $settings['end_time'] }}</span></li>
                            <li><strong class="text-red-300">{{ __('Duration of a Single Session') }}:</strong> <span class="text-red-600">{{ $settings['session_duration'] }} hour</span></li>
                            <li><strong class="text-red-300">{{ __('Delay Between Sessions') }}:</strong> <span class="text-red-600">{{ $settings['delay_between_sessions'] }} minutes</span></li>
                            <li><strong class="text-red-300">{{ __('Start of the Break') }}:</strong> <span class="text-red-600">{{ $settings['break_start'] }}</span></li>
                            <li><strong class="text-red-300">{{ __('End of the Break') }}:</strong> <span class="text-red-600">{{ $settings['break_end'] }}</span></li>
                            <li><strong class="text-red-300">{{ __('OFF DAYS') }}:</strong> <span class="text-red-600">{{ implode(', ', $settings['off_days']) }}</span></li>
                        </ul>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
