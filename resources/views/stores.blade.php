@extends('layouts.app')
@use('App\Support\Site')

@section('title', 'All Stores A-Z')
@section('canonical', Site::url('stores'))

@section('content')
<main class="container">
    <h1 class="page-title">All Stores</h1>
    <nav class="letters">
        @foreach ($groups->keys() as $letter)
            <a href="#letter-{{ $letter }}">{{ $letter }}</a>
        @endforeach
    </nav>
    <div class="box">
        @foreach ($groups as $letter => $stores)
            <section class="az" id="letter-{{ $letter }}">
                <div class="az__letter">{{ $letter }}</div>
                <div class="az__grid">
                    @foreach ($stores as $store)
                        <a href="{{ $store->pageUrl() }}">{{ $store->name }}</a>
                    @endforeach
                </div>
            </section>
        @endforeach
    </div>
</main>
@endsection
