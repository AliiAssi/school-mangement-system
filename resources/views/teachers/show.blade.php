<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Teacher Information') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-900 overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- User Information -->
                        <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-semibold mb-4">User Information</h3>
                            <p class="text-gray-700 dark:text-gray-300"><strong>Name:</strong> {{ $teacher->name }}</p>
                            <p class="text-gray-700 dark:text-gray-300"><strong>Email:</strong> {{ $teacher->email }}</p>
                        </div>

                        <!-- Subjects -->
                        <div class="bg-gray-50 dark:bg-gray-800 p-6 rounded-lg shadow-md">
                            <h3 class="text-xl font-semibold mb-4">Subjects</h3>
                            @if($teacher->subjects->isEmpty())
                                <p class="text-gray-700 dark:text-gray-300">No subjects assigned.</p>
                            @else
                                <div class="space-y-4">
                                    @foreach($teacher->subjects->unique('id') as $subject)
                                        <div class="bg-gray-100 dark:bg-gray-700 p-4 rounded-lg shadow-sm">
                                            <h4 class="text-lg font-semibold mb-2">{{ $subject->name }}</h4>
                                            <div class="space-y-2">
                                                @foreach($subject->classes as $class)
                                                    @php
                                                        // Fetch weekly sessions directly
                                                        $pivotData = \App\Models\UserSubjectClass::where('user_id', $teacher->id)
                                                            ->where('subject_id', $subject->id)
                                                            ->where('class_id', $class->id)
                                                            ->first();
                                                    @endphp
                                                    <div class="dark:bg-gray-600 p-3 border border-gray-300 dark:border-gray-500 rounded-md shadow-sm">
                                                        <p class="text-gray-800 dark:text-gray-200"><strong>Grade:</strong> {{ $class->grade ?? 'N/A' }}</p>
                                                        <p class="text-gray-800 dark:text-gray-200"><strong>Weekly Sessions:</strong> {{ $pivotData ? $pivotData->weekly_sessions : 'N/A' }}</p>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
