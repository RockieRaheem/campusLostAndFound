@extends('layouts.app')

@section('title', 'Welcome to Campus Finder')

@section('content')
    <div class="relative bg-white overflow-hidden min-h-[calc(100vh-64px)] flex flex-col justify-center">
        <div class="max-w-7xl mx-auto">
            <div class="relative z-10 pb-8 bg-white sm:pb-16 md:pb-20 lg:max-w-2xl lg:w-full lg:pb-28 xl:pb-32">
                <main class="mt-10 mx-auto max-w-7xl px-4 sm:mt-12 sm:px-6 md:mt-16 lg:mt-20 lg:px-8 xl:mt-28">
                    <div class="sm:text-center lg:text-left">
                        <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 sm:text-5xl md:text-6xl">
                            <span class="block xl:inline">Smart Campus</span>
                            <span class="block text-primary xl:inline">Lost & Found</span>
                        </h1>
                        <p class="mt-3 text-base text-gray-500 sm:mt-5 sm:text-lg sm:max-w-xl sm:mx-auto md:mt-5 md:text-xl lg:mx-0">
                            Did you lose something important? Or did you find an item that belongs to someone else? Our platform easily connects campus students to securely claim and return lost items.
                        </p>
                        <div class="mt-5 sm:mt-8 sm:flex sm:justify-center lg:justify-start">
                            <div class="rounded-md shadow">
                                <a href="{{ auth()->check() ? route('items.index') : route('login') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-primary hover:bg-primary/90 md:py-4 md:text-lg md:px-10 transition">
                                    Browse Items
                                </a>
                            </div>
                            @guest
                            <div class="mt-3 sm:mt-0 sm:ml-3">
                                <a href="{{ route('register') }}" class="w-full flex items-center justify-center px-8 py-3 border border-slate-300 text-base font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 md:py-4 md:text-lg md:px-10 transition">
                                    Create Account
                                </a>
                            </div>
                            @endguest
                        </div>
                    </div>
                </main>
            </div>
        </div>
        <div class="lg:absolute lg:inset-y-0 lg:right-0 lg:w-1/2 bg-slate-50 content-center justify-center hidden lg:flex border-l border-slate-100">
            <span class="material-symbols-outlined text-[20rem] text-slate-200">manage_search</span>
        </div>
    </div>
@endsection