<x-app-layout>
    <x-slot name="header">
        <!-- Banner Section -->
        <div class="relative bg-gray-800 text-white overflow-hidden">
            <!-- Banner Image -->
            <img src="{{ asset('assets/banner.jpg') }}" alt="Banner Image" class="w-full h-64 object-cover banner-slide-in">

            <!-- Banner Content -->
            <div class="absolute inset-0 flex items-center justify-center text-center banner-slide-in">
                <div>
                    <h1 class="text-4xl font-bold mb-4">Welcome!</h1>
                    <p class="text-xl text-gray-800 dark:text-gray-200">
                        Discover your deals when you
                        <a href="/login" class="text-blue-500 hover:text-blue-700 font-semibold transition-colors duration-300">
                            login
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("School timetables are essential tools for managing and organizing the academic schedule of students and teachers. They play a crucial role in ensuring that the educational experience is structured and efficient, facilitating a smooth flow of the academic day!") }}
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Custom animation for the banner */
        @keyframes slideIn {
            from {
                transform: translateY(-100%);
            }
            to {
                transform: translateY(0);
            }
        }

        .banner-slide-in {
            animation: slideIn 1s ease-out;
        }
    </style>
</x-app-layout>
