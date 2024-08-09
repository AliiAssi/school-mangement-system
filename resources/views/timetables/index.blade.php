<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('TimeTables') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                @if($errors->any())
                    <div class="bg-red-500 text-white p-4 mb-4 rounded">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                    <!-- Create Button -->
                    <div class="mb-6">
                        <a href="{{ route('timetables.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-green-600 border border-gray-300 rounded-md shadow-sm text-base font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-green-500 dark:border-gray-600 dark:hover:bg-green-600 dark:focus:ring-green-400">
                            Add New TimeTable
                        </a>
                        <a href="{{ route('automatically') }}"
                            class="inline-flex items-center px-4 py-2 bg-purple-600 border border-gray-300 rounded-md shadow-sm text-base font-medium text-white hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:bg-purple-500 dark:border-gray-600 dark:hover:bg-purple-600 dark:focus:ring-green-400">
                            Generate TimeTables For All Classes Automatically
                        </a>
                        <a href="{{ route('export') }}"
                            class="inline-flex items-center px-4 py-2 bg-rose-600 border border-gray-300 rounded-md shadow-sm text-base font-medium text-white hover:bg-rose-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-rose-500 dark:bg-rose-500 dark:border-gray-600 dark:hover:bg-rose-600 dark:focus:ring-rose-400">
                            Export To Pdf
                        </a>
                    </div>

                    <!-- Filter Form -->
                    <form method="GET" action="{{ route('timetables.index') }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <!-- Class Selection -->
                            <div>
                                <label for="class_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class</label>
                                <select id="class_id" name="class_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                                    <option value="">All Classes</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}"
                                            {{ $class->id == $classId ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Submit Button -->
                            <div class="flex items-center justify-end">
                                <button type="submit"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-500">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </form>

                    <!-- TimeTable Layout -->
                    @if ($classId)
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
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
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
                                                    $slots = $timetables->filter(function ($item) use ($day, $time) {
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
                                                            onclick="window.location.href='/timetables/{{ $slot->id }}'"
                                                        >
                                                            <span class="font-medium">{{ $slot->teacher->name }}</span>
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
                    @else
                        <div class="mt-6 text-gray-600 dark:text-gray-400">
                            @foreach ($classes as $class)
                                <div class="mb-12">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                                        {{ $class->name }} TimeTable
                                    </h3>
                                    @php
                                        // Fetch timetables for the current class
                                        $classTimetables = $timetables->filter(function ($item) use ($class) {
                                            return $item->class_id == $class->id;
                                        });
                                    @endphp
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
                                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-900 dark:divide-gray-700">
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
                                                                $slots = $classTimetables->filter(function ($item) use ($day, $time) {
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
                                                                        <span class="font-medium">{{ $slot->teacher->name }}</span>
                                                                        <span class="text-gray-500">
                                                                            {{ (int) explode(':', $slot->start_time)[0] }}:00 -
                                                                            {{ (int) explode(':', $slot->end_time)[0] }}:00
                                                                        </span>
                                                                        <span class="text-gray-100">{{ $slot->subject->name }}</span>
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
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
