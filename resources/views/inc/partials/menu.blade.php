<li class="{{ isActiveMenu($menu) }}">
    <a href="{{ $menu->url ?: '#' }}" title="{{ $menu->title }}" data-filter-tags="{{ $menu->title }}">
        @if ($menu->icon)
            <i class="{{ $menu->icon }}"></i>
        @endif
        <span class="nav-link-text">{{ $menu->title }}</span>
    </a>
    @if ($menu->children->count() > 0)
        <ul>
            @foreach ($menu->children as $child)
                @can($child->permission)
                    <li class="{{ set_active($child->url) }}">
                        @include('inc.partials.menu', ['menu' => $child])
                    </li>
                @endcan
            @endforeach
        </ul>
    @endif
</li>
