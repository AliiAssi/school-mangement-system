<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Subject') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('subjects.update', $subject->id) }}">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Name') }}</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $subject->name) }}" 
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-black focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" 
                                   required>
                            @error('name')
                                <p class="mt-1 text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Abbreviation -->
                        <div class="mb-6">
                            <label for="abbreviation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Abbreviation') }}</label>
                            <input type="text" name="abbreviation" id="abbreviation" value="{{ old('abbreviation', $subject->abbreviation) }}" 
                                   class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-black focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" 
                                   required>
                            @error('abbreviation')
                                <p class="mt-1 text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mb-6">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Description') }}</label>
                            <textarea name="description" id="description" rows="4" 
                                      class="mt-1 block w-full border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-black focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50" 
                                      required>{{ old('description', $subject->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Classes -->
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Classes') }}</label>
                            @if ($classes->isEmpty())
                                <p class="text-gray-500 dark:text-gray-400">{{ __('No classes available. Please create classes first.') }}</p>
                                <a href="{{ route('classes.create') }}" 
                                   class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 transition mt-2">
                                    {{ __('Create Classes') }}
                                </a>
                            @else
                                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 mt-2">
                                    @foreach ($classes as $class)
                                        <div class="flex items-center space-x-4 p-4 border border-gray-200 dark:border-gray-700 rounded-md shadow-sm bg-white dark:bg-gray-900">
                                            <input type="checkbox" name="classes[{{ $class->id }}][id]" value="{{ $class->id }}" 
                                                   id="class-{{ $class->id }}" 
                                                   class="form-checkbox"
                                                   {{ $subject->classes->contains($class->id) ? 'checked' : '' }}>
                                            <label for="class-{{ $class->id }}" 
                                                   class="text-sm text-gray-700 dark:text-gray-300">{{ $class->name }}</label>
                                            <input type="number" name="classes[{{ $class->id }}][sessions_required]" 
                                                   placeholder="{{ __('Sessions Required') }}" 
                                                   value="{{ $subject->classes->find($class->id)->pivot->required_sessions ?? '' }}" 
                                                   class="border-gray-300 dark:border-gray-700 rounded-md shadow-sm text-black w-24">
                                        </div>
                                    @endforeach
                                </div>
                                @error('classes')
                                    <p class="mt-1 text-red-600 dark:text-red-400 text-sm">{{ $message }}</p>
                                @enderror
                            @endif
                        </div>

                        <!-- Submit Button -->
                        <div class="mb-6">
                            <button type="submit" 
                                    class="px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:outline-none focus:ring focus:ring-blue-300 transition">
                                {{ __('Update Subject') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
