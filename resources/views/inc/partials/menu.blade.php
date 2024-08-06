@php
    // Kumpulkan URL children dan sub-children menu
    $childrenUrls = $menu->children
        ->flatMap(function ($child) {
            return $child->children->pluck('url')->merge([$child->url]);
        })
        ->toArray();

    // Tambahkan juga URL parent menu
    $urls = array_merge([$menu->url], $childrenUrls);

    // Set class active main menu berdasarkan array URL children dan sub-children
    $isActiveMainMenu = set_active_mainmenu($urls);
    $isActiveSubMenu = set_active($menu->url);
@endphp

<li class="{{ $menu->children->count() > 0 ? $isActiveMainMenu : $isActiveSubMenu }}">
    <a href="{{ $menu->url ?: '#' }}" title="{{ $menu->title }}" data-filter-tags="{{ $menu->title }}">
        @if ($menu->icon)
            <i class="{{ $menu->icon }}"></i>
        @endif
        <span class="nav-link-text">{{ $menu->title }}</span>
    </a>
    @if ($menu->children->count() > 0)
        <ul>
            @foreach ($menu->children as $i => $child)
                @can($child->permission)
                    @include('inc.partials.menu', ['menu' => $child])
                @endcan
            @endforeach
        </ul>
    @endif
</li>
