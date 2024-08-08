<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Teacher') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('teachers.update', $teacher->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Grid Layout for Form -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <!-- User Data Column -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4">{{ __('User Information') }}</h3>

                                <!-- Name -->
                                <div class="mb-4">
                                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name') }}</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $teacher->name) }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-black focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    @error('name')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-4">
                                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Email') }}</label>
                                    <input type="email" name="email" id="email" value="{{ old('email', $teacher->email) }}" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-black focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    @error('email')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="mb-4">
                                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Password') }}</label>
                                    <input type="password" name="password" id="password" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-black focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                    @error('password')
                                        <span class="text-red-500 text-sm">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <!-- Subjects Column -->
                            <div>
                                <h3 class="text-lg font-semibold mb-4">{{ __('Subjects and Grades') }}</h3>

                                <div x-data="{ openSubject: null }">
                                    @foreach($subjects as $subject)
                                        <div class="mb-6">
                                            <button @click="openSubject === {{ $subject->id }} ? openSubject = null : openSubject = {{ $subject->id }}" type="button" class="w-full text-left py-2 px-4 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:hover:bg-gray-600 dark:text-gray-300">
                                                {{ $subject->name }}
                                            </button>
                                            
                                            <!-- Popup for Subject Grades -->
                                            <div x-show="openSubject === {{ $subject->id }}" x-cloak class="mt-4 p-4 border border-gray-300 rounded-md bg-gray-50 dark:bg-gray-700">
                                                <h4 class="text-md font-medium mb-2">{{ __('Grades for') }} {{ $subject->name }}</h4>
                                                <div class="space-y-4">
                                                    @foreach($subject->classes as $class)
                                                        <div class="flex items-center mb-2">
                                                            <!-- Checkbox -->
                                                            <input type="checkbox" name="subjects[{{ $subject->id }}][grades][{{ $class->id }}][selected]" id="subject_{{ $subject->id }}_class_{{ $class->id }}" value="1" {{ old('subjects.' . $subject->id . '.grades.' . $class->id . '.weekly_sessions', $teacherSubjects[$subject->id]['grades'][$class->id]['weekly_sessions'] ?? 0) > 0 ? 'checked' : '' }} class="h-4 w-4 border-gray-300 dark:border-gray-600 rounded">
                                                            <label for="subject_{{ $subject->id }}_class_{{ $class->id }}" class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $class->name }} (Grade: {{ $class->grade }})</label>
                                                            
                                                            <!-- Number Input -->
                                                            <input type="number" name="subjects[{{ $subject->id }}][grades][{{ $class->id }}][weekly_sessions]" id="weekly_sessions_{{ $subject->id }}_class_{{ $class->id }}" value="{{ old('subjects.' . $subject->id . '.grades.' . $class->id . '.weekly_sessions', $teacherSubjects[$subject->id]['grades'][$class->id]['weekly_sessions'] ?? 0) }}" min="0" class="ml-2 w-24 border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-black focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                        </div>

                        <div class="mb-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                {{ __('Update Teacher') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
