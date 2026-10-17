@extends('layouts.app')

@section('title', 'Search: ' . $q)

@section('content')
<main class="container">
    <h1 class="page-title">Search results for “{{ $q }}”</h1>
    @if ($stores->isEmpty())
        <p class="muted">No stores found. Try another name.</p>
    @else
        <div class="store-grid">
            @foreach ($stores as $store)
                @include('partials.store-tile', ['store' => $store])
            @endforeach
        </div>
    @endif
</main>
@endsection
