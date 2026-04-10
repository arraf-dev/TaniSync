@extends('layouts.base')

@php
    $user = auth()->user();
    $role = $user?->role;
@endphp

@section('body')
    <div class="min-h-screen md:flex">
        @include('layouts.partials.sidebar', ['role' => $role, 'user' => $user])
        <div class="min-w-0 flex-1">
            @include('layouts.partials.topbar', ['role' => $role, 'user' => $user, 'pageTitle' => $pageTitle ?? 'Dashboard'])
            <main class="mx-auto max-w-7xl px-5 pb-28 pt-6 md:px-8 md:pb-10">
                @yield('content')
            </main>
        </div>
        @include('layouts.partials.mobile-nav', ['role' => $role])
    </div>
@endsection
