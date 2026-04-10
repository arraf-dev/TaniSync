@extends('layouts.base')

@section('body')
    @include('layouts.partials.public-header')
    <main>
        @yield('content')
    </main>
    @include('layouts.partials.public-footer')
@endsection
