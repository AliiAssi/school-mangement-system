<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Teachers') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-300 text-green-700 rounded-md">
                            {{ session('success') }}
                        </div>
                    @endif

                    <!-- Add New Teacher Button -->
                    <div class="flex justify-between mb-4">
                        <a href="{{ route('teachers.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-md border border-blue-700 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            Add New Teacher
                        </a>
                    </div>

                    <!-- Teachers Table -->
                    <table class="min-w-full bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700">
                        <thead>
                            <tr>
                                <th class="py-2 px-4 border-b text-left">{{ __('Name') }}</th>
                                <th class="py-2 px-4 border-b text-left">{{ __('Email') }}</th>
                                <th class="py-2 px-4 border-b text-left">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($teachers as $teacher)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $teacher->name }}</td>
                                <td class="py-2 px-4 border-b">{{ $teacher->email }}</td>
                                <td class="py-2 px-4 border-b flex items-center space-x-4">
                                    <a href="{{ route('teachers.show', $teacher) }}" class="text-blue-500 hover:text-blue-700">{{ __('View') }}</a>
                                    <a href="{{ route('teachers.edit', $teacher) }}" class="text-yellow-500 hover:text-yellow-700">{{ __('Edit') }}</a>
                                    <form action="{{ route('teachers.destroy', $teacher) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">{{ __('Delete') }}</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
