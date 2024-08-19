<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- Display any success message -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 text-green-800 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Display validation errors -->
                    @if ($errors->any())
                        <div class="mb-4 p-4 bg-red-100 text-red-800 rounded-md">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('settings.update',['setting' => 'dummy-id']) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <ul class="list-disc pl-5 space-y-4">
                            <li>
                                <label for="start_time" class="text-gradient text-xl">{{ __('Start Time of the School') }}:</label>
                                <input type="time" id="start_time" name="start_time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-900 dark:text-gray-100" value="{{ old('start_time', '08:00') }}" required>
                            </li>
                            <li>
                                <label for="end_time" class="text-gradient text-xl">{{ __('End Time of the School') }}:</label>
                                <input type="time" id="end_time" name="end_time" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-900 dark:text-gray-100" value="{{ old('end_time', '17:00') }}" required>
                            </li>
                            <li>
                                <label for="session_duration" class="text-gradient text-xl">{{ __('Duration of a Single Session / hours') }}:</label>
                                <input type="number" id="session_duration" min="1" max="3" name="session_duration" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-900 dark:text-gray-100" value="{{ old('session_duration', 1) }}" required>
                            </li>
                            <li>
                                <label for="delay_between_sessions" class="text-gradient text-xl">{{ __('Delay Between Sessions / mins') }}:</label>
                                <input type="number" min="0" max="5" id="delay_between_sessions" name="delay_between_sessions" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-900 dark:text-gray-100" value="{{ old('delay_between_sessions', 5) }}" required>
                            </li>
                            <li>
                                <label for="break_start" class="text-gradient text-xl">{{ __('Start of the Break') }}:</label>
                                <input type="time" id="break_start" name="break_start" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-900 dark:text-gray-100" value="{{ old('break_start', '12:00') }}" required>
                            </li>
                            <li>
                                <label for="break_end" class="text-gradient text-xl">{{ __('End of the Break') }}:</label>
                                <input type="time" id="break_end" name="break_end" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-900 dark:text-gray-100" value="{{ old('break_end', '12:30') }}" required>
                            </li>
                            <li>
                                <span class="text-gradient text-xl">{{ __('OFF DAYS') }}:</span>
                                <div class="mt-2 flex flex-wrap space-x-4">
                                    @foreach(['sunday' => 'Sunday', 'monday' => 'Monday', 'tuesday' => 'Tuesday', 'wednesday' => 'Wednesday', 'thursday' => 'Thursday', 'friday' => 'Friday', 'saturday' => 'Saturday'] as $key => $day)
                                        <div class="flex items-center">
                                            <input type="checkbox" id="{{ $key }}" name="off_days[]" value="{{ $key }}" {{ in_array($key, old('off_days', ['sunday','saturday'])) ? 'checked' : '' }} class="mr-2 leading-tight">
                                            <label for="{{ $key }}" class="text-gray-800 dark:text-gray-200">{{ $day }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </li>
                        </ul>
                        <div class="mt-6">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 transition">
                                {{ __('Save Settings') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
