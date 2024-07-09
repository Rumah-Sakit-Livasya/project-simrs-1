@php
    // Kumpulkan URL children menu
    $childrenUrls = $menu->children->pluck('url')->toArray();
    // Tambahkan juga URL parent menu
    $urls = array_merge([$menu->url], $childrenUrls);

    // Set class active main menu berdasarkan array URL children
    $isActive = $menu->children->count() > 0 ? set_active_mainmenu($urls) : set_active($menu->url);
@endphp

<li class="{{ $isActive }}">
    <a href="{{ $menu->url ?: '#' }}" title="{{ $menu->title }}" data-filter-tags="{{ $menu->title }}">
        @if ($menu->icon)
            <i class="{{ $menu->icon }}"></i>
        @endif
        <span class="nav-link-text">{{ $menu->title }}</span>
    </a>
    @if ($menu->children->count() > 0)
        <ul>
            @foreach ($menu->children as $child)
                @include('inc.partials.menu', ['menu' => $child])
            @endforeach
        </ul>
    @endif
</li>
