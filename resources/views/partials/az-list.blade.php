{{-- Stores grouped by first letter: 2 · 4 · A · B ... (like worthepenny category pages) --}}
@forelse ($groups as $letter => $stores)
    <section class="az">
        <div class="az__letter">{{ $letter }}</div>
        <div class="az__grid">
            @foreach ($stores as $store)
                <a href="{{ $store->pageUrl() }}">{{ $store->name }}</a>
            @endforeach
        </div>
    </section>
@empty
    <p class="muted">No stores here yet.</p>
@endforelse
