<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('TimeTable Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <!-- TimeTable Details -->
                    <div class="mb-6">
                        <div class="text-lg font-semibold mb-2">
                            {{ $timetable->teacher->name }}'s Timetable
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            Subject: {{ $timetable->subject->name }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            Day: {{ $timetable->day_of_week }}
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                            Time: {{ $timetable->start_time }} - {{ $timetable->end_time }}
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex space-x-4">
                        <a href="{{ route('timetables.edit', $timetable->id) }}"
                            class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-gray-300 rounded-md shadow-sm text-base font-medium text-white hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 dark:bg-yellow-500 dark:border-gray-600 dark:hover:bg-yellow-600 dark:focus:ring-yellow-400">
                            Edit
                        </a>

                        <!-- Delete Form -->
                        <form action="{{ route('timetables.destroy', $timetable->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-gray-300 rounded-md shadow-sm text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 dark:bg-red-500 dark:border-gray-600 dark:hover:bg-red-600 dark:focus:ring-red-400">
                                Delete
                            </button>
                        </form>

                        <a href="{{ route('timetables.index') }}"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-gray-300 rounded-md shadow-sm text-base font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:bg-gray-500 dark:border-gray-600 dark:hover:bg-gray-600 dark:focus:ring-gray-400">
                            Return
                        </a>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
