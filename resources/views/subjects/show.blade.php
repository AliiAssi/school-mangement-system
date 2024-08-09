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
                        <h3 class="text-lg font-semibold">{{ __('Subject: ') }} {{ $subject->name }}</h3>
                        <p class="mt-2">{{ __('Description: ') }} {{ $subject->description }}</p>
                        <p class="mt-2">{{ __('Abbreviation: ') }} {{ $subject->abbreviation }}</p>
                    </div>
                    
                    <!-- Teachers and Grades -->
                    <div class="mt-6">
                        <h4 class="text-lg font-semibold">{{ __('Teachers') }}</h4>
                        @if($teachers->isEmpty())
                            <p>{{ __('No teachers assigned to this subject.') }}</p>
                        @else
                            <ul class="list-disc ml-5">
                                @foreach($teachers as $teacher)
                                    <li class="mb-2">
                                        {{ $teacher->name }}
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>

                    <!-- Back to subjects list -->
                    <div class="mt-6">
                        <a href="{{ route('subjects.index') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-md shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            {{ __('Back to Subjects') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
