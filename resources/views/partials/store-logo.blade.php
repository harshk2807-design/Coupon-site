{{-- Store logo, or the first letter in a coloured circle if there is no logo --}}
@if ($store->logoUrl())
    <img class="store-logo" src="{{ $store->logoUrl() }}" alt="{{ $store->name }}" style="width:{{ $size ?? 64 }}px;height:{{ $size ?? 64 }}px" loading="lazy">
@else
    <span class="store-letter" style="width:{{ $size ?? 64 }}px;height:{{ $size ?? 64 }}px;font-size:{{ ($size ?? 64) * 0.45 }}px">{{ $store->letter() }}</span>
@endif
