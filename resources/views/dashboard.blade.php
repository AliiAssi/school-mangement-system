<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (Auth::user() && Auth::user()->isAdmin)
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <!-- Subjects Card -->
                            <a href="{{ route('subjects.index') }}"
                                class="group bg-gradient-to-r from-blue-600 to-blue-700 dark:from-blue-500 dark:to-blue-900 text-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 ease-in-out">
                                <div class="p-6 flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold">{{ __('Subjects') }}</h3>
                                        <p class="mt-1 text-sm">{{ __('Manage all subjects here.') }}</p>
                                    </div>
                                    <svg class="w-6 h-6 text-white group-hover:text-blue-200 transition duration-300 ease-in-out"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </a>

                            <!-- Classes Card -->
                            <a href="{{ route('classes.index') }}"
                                class="group bg-gradient-to-r from-green-600 to-green-700 dark:from-green-500 dark:to-green-900 text-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-700 ease-in-out">
                                <div class="p-6 flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold">{{ __('Classes') }}</h3>
                                        <p class="mt-1 text-sm">{{ __('Manage all classes here.') }}</p>
                                    </div>
                                    <svg class="w-6 h-6 text-white group-hover:text-green-200 transition duration-300 ease-in-out"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </a>

                            <!-- Teachers Card -->
                            <a href="{{ route('teachers.index') }}"
                                class="group bg-gradient-to-r from-teal-600 to-teal-700 dark:from-teal-500 dark:to-teal-900 text-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 ease-in-out">
                                <div class="p-6 flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold">{{ __('Teachers') }}</h3>
                                        <p class="mt-1 text-sm">{{ __('Manage all teachers here.') }}</p>
                                    </div>
                                    <svg class="w-6 h-6 text-white group-hover:text-teal-200 transition duration-300 ease-in-out"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </a>
                            <!-- Timetables Card -->
                            <a href="{{ route('timetables.index') }}"
                                class="group bg-gradient-to-r from-yellow-600 to-yellow-700 dark:from-yellow-500 dark:to-yellow-900 text-white rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 ease-in-out">
                                <div class="p-6 flex items-center justify-between">
                                    <div>
                                        <h3 class="text-lg font-semibold">{{ __('Timetables') }}</h3>
                                        <p class="mt-1 text-sm">{{ __('Manage all timetables here.') }}</p>
                                    </div>
                                    <svg class="w-6 h-6 text-white group-hover:text-yellow-200 transition duration-300 ease-in-out"
                                        fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </a>
                        </div>
                    @else
                        <div class="text-center text-gray-900 dark:text-gray-100">
                            @if (session('error'))
                                <div class="bg-red-500 text-white p-4 rounded mb-4">
                                    {{ session('error') }}
                                </div>
                            @endif
                            <h1>{{ __('Welcome, teacher ') . Auth::user()->name }}</h1>
                            <br>
                            <hr />
                            <br>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-800">
                                        <tr>
                                            <th
                                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Day</th>
                                            @for ($hour = 8; $hour <= 17; $hour++)
                                                <th
                                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    {{ $hour }}:00</th>
                                            @endfor
                                        </tr>
                                    </thead>
                                    <tbody
                                        class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
                                        @foreach (['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'] as $day)
                                            <tr>
                                                <!-- Day of the week -->
                                                <td
                                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                    {{ $day }}
                                                </td>

                                                <!-- Hours of the day -->
                                                @for ($hour = 8; $hour <= 17; $hour++)
                                                    @php
                                                        // Generate time slot label
                                                        $time = sprintf('%02d:00', $hour);

                                                        // Find timetable slots for the current day and time
                                                        $slots = $timetables->filter(function ($item) use (
                                                            $day,
                                                            $time,
                                                        ) {
                                                            $slotStart = (int) explode(':', $item->start_time)[0];
                                                            $slotEnd = (int) explode(':', $item->end_time)[0];
                                                            $currentHour = (int) explode(':', $time)[0];
                                                            return $item->day_of_week === $day &&
                                                                $slotStart <= $currentHour &&
                                                                $slotEnd > $currentHour;
                                                        });
                                                    @endphp

                                                    <!-- Table cell with conditional styling -->
                                                    <td
                                                        class="px-6 py-4 whitespace-nowrap text-sm {{ $slots->isNotEmpty() ? 'bg-blue-100 dark:bg-blue-700' : 'text-gray-700 dark:text-gray-400' }}">
                                                        @foreach ($slots as $slot)
                                                            <div class="flex flex-col space-y-1 text-sm cursor-pointer hover:bg-gray-100 dark:hover:bg-blue-900"
                                                                onclick="window.location.href='/timetables/{{ $slot->id }}'">
                                                                <span
                                                                    class="font-medium">{{ $slot->teacher->name }}</span>
                                                                <span class="text-gray-500">
                                                                    {{ (int) explode(':', $slot->start_time)[0] }}:00 -
                                                                    {{ (int) explode(':', $slot->end_time)[0] }}:00
                                                                </span>
                                                                <span
                                                                    class="text-gray-100">{{ $slot->subject->name }}</span>
                                                            </div>
                                                        @endforeach
                                                    </td>
                                                @endfor
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
