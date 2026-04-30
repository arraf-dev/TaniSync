@extends('layouts.base')

@php
    $user = auth()->user();
    $role = $user?->role;
@endphp

@section('body')
    <div class="app-shell md:flex">
        @include('layouts.partials.sidebar', ['role' => $role, 'user' => $user])
        <div class="min-w-0 flex-1">
            @include('layouts.partials.topbar', ['role' => $role, 'user' => $user, 'pageTitle' => $pageTitle ?? 'Dashboard'])
            <main class="app-main">
                @yield('content')
            </main>
        </div>
        @include('layouts.partials.mobile-nav', ['role' => $role])
    </div>
@endsection
