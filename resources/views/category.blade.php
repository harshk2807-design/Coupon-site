@extends('layouts.app')
@use('App\Support\Site')

@section('title', $category->name . ' Coupons & Promo Codes')
@section('description', 'All ' . $category->name . ' stores with verified coupon codes and deals.')
@section('canonical', $category->url())

@section('content')
<div class="container">
    {{-- Breadcrumb: All Categories › Games › Video Games --}}
    <nav class="breadcrumb">
        <a href="{{ Site::url('categories') }}">All Categories</a>
        @if ($category->parent)
            › <a href="{{ $category->parent->url() }}">{{ $category->parent->name }}</a>
        @endif
        › <span>{{ $category->name }}</span>
    </nav>

    <div class="cat-layout">
        {{-- Sidebar: the main category and all its subcategories --}}
        <aside class="cat-side">
            <a class="cat-side__main {{ $main->is($category) ? 'is-active' : '' }}" href="{{ $main->url() }}">{{ $main->name }}</a>
            @foreach ($main->children as $sub)
                <a class="cat-side__sub {{ $sub->is($category) ? 'is-active' : '' }}" href="{{ $sub->url() }}">{{ $sub->name }}</a>
            @endforeach
        </aside>

        {{-- Stores A-Z --}}
        <main class="cat-main">
            <h1>All {{ $category->name }}</h1>
            @include('partials.az-list', ['groups' => $groups])
        </main>
    </div>
</div>
@endsection
