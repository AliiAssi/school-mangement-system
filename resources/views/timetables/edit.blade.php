<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit TimeTable') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <!-- Information Alert -->
                    <div class="bg-blue-50 border-l-4 border-blue-400 text-blue-700 p-4 mb-6" role="alert">
                        <p class="font-medium">Important Information:</p>
                        <p>Select a class and subject to see the available teachers and complete the form.</p>
                    </div>

                    <!-- Form to Select Class and Subject -->
                    <form method="GET" action="{{ route('timetables.edit', $timeTable->id) }}">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Class Selection -->
                            <div>
                                <label for="class_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Class</label>
                                <select id="class_id" name="class_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:focus:border-indigo-500 dark:focus:ring-indigo-500" onchange="this.form.submit()">
                                    <option value="" disabled>Select a class</option>
                                    @foreach ($classes as $class)
                                        <option value="{{ $class->id }}" {{ $class->id == $selectedClass ? 'selected' : '' }}>
                                            {{ $class->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Subject Selection -->
                            <div>
                                <label for="subject_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Subject</label>
                                <select id="subject_id" name="subject_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:focus:border-indigo-500 dark:focus:ring-indigo-500" onchange="this.form.submit()">
                                    <option value="" disabled>Select a subject</option>
                                    @foreach ($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ $subject->id == $selectedSubject ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>

                    <!-- Form to Update TimeTable -->
                    @if($selectedClass && $selectedSubject && $teachers->count() > 0)
                        <form action="{{ route('timetables.update', $timeTable->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <input type="hidden" name="class_id" value="{{ $selectedClass }}">
                            <input type="hidden" name="subject_id" value="{{ $selectedSubject }}">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
                                <!-- Teacher Selection -->
                                <div>
                                    <label for="user_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Teacher</label>
                                    <select id="user_id" name="user_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                                        <option value="" disabled>Select a teacher</option>
                                        @foreach ($teachers as $teacher)
                                            <option value="{{ $teacher->id }}" {{ $teacher->id == $timeTable->user_id ? 'selected' : '' }}>
                                                {{ $teacher->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Start Time -->
                                <div>
                                    <label for="start_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Time</label>
                                    <input type="time" id="start_time" name="start_time" value="{{ $timeTable->start_time }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:focus:border-indigo-500 dark:focus:ring-indigo-500" required>
                                </div>

                                <!-- End Time -->
                                <div>
                                    <label for="end_time" class="block text-sm font-medium text-gray-700 dark:text-gray-300">End Time</label>
                                    <input type="time" id="end_time" name="end_time" value="{{ $timeTable->end_time }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:focus:border-indigo-500 dark:focus:ring-indigo-500" required>
                                </div>

                                <!-- Day of the Week -->
                                <div>
                                    <label for="day_of_week" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Day of the Week</label>
                                    <select id="day_of_week" name="day_of_week" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 dark:focus:border-indigo-500 dark:focus:ring-indigo-500">
                                        <option value="" disabled>Select a day</option>
                                        <option value="Monday" {{ $timeTable->day_of_week == 'Monday' ? 'selected' : '' }}>Monday</option>
                                        <option value="Tuesday" {{ $timeTable->day_of_week == 'Tuesday' ? 'selected' : '' }}>Tuesday</option>
                                        <option value="Wednesday" {{ $timeTable->day_of_week == 'Wednesday' ? 'selected' : '' }}>Wednesday</option>
                                        <option value="Thursday" {{ $timeTable->day_of_week == 'Thursday' ? 'selected' : '' }}>Thursday</option>
                                        <option value="Friday" {{ $timeTable->day_of_week == 'Friday' ? 'selected' : '' }}>Friday</option>
                                        <option value="Saturday" {{ $timeTable->day_of_week == 'Saturday' ? 'selected' : '' }}>Saturday</option>
                                        <option value="Sunday" {{ $timeTable->day_of_week == 'Sunday' ? 'selected' : '' }}>Sunday</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="mt-6">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:bg-blue-500 dark:hover:bg-blue-600 dark:focus:ring-blue-400">
                                    Update TimeTable
                                </button>
                            </div>
                        </form>
                    @elseif($selectedClass && !$subjects->count())
                        <div class="mt-6 text-red-600">No subjects available for the selected class.</div>
                    @elseif($selectedSubject && !$teachers->count())
                        <div class="mt-6 text-red-600">No teachers available for the selected subject.</div>
                    @endif

                    <!-- Display errors from the update function -->
                    @if ($errors->any())
                        <script>
                            window.scrollTo(0, document.body.scrollHeight);
                        </script>       
                        <div class="mt-6 bg-red-50 border-l-4 border-red-400 text-red-700 p-4" role="alert">
                            <p class="font-medium">There were some problems with your input:</p>
                            <ul class="list-disc pl-5 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
