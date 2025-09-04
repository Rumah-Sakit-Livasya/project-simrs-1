`<!-- BEGIN Left Aside -->
<aside class="page-sidebar">
    <div class="page-logo d-flex justify-content-center text-center">
        <a href="#"
            class="page-logo-link press-scale-down d-flex align-items-center justify-content-center position-relative"
            data-toggle="modal" data-target="#modal-shortcut">
            <img src="{{ asset('/img/logo.png') }}" alt="Laravel" aria-roledescription="logo" style="width: 45px">
            <div class="text-left ml-2">
                <span class="page-logo-text text-left font-weight-bold ml-0">
                    @switch(session('app_type', 'hr'))
                        @case('hr')
                            SMART HR
                        @break

                        @case('simrs')
                            S I M R S
                        @break

                        @case('inventaris')
                            INVENTARIS
                        @break

                        @case('library')
                            KEPUSTAKAAN
                        @break

                        @case('keuangan')
                            KEUANGAN
                        @break

                        @case('mutu')
                            MUTU
                        @break

                        @default
                            SMART HR
                    @endswitch
                </span>
                <p class="mb-0 text-small text-white"> {{ auth()->user()->employee->company->name }}</p>
            </div>
        </a>
    </div>
    <!-- BEGIN PRIMARY NAVIGATION -->
    <nav id="js-primary-nav" class="primary-nav" role="navigation" style="height: 100%">
        <div class="nav-filter mt-3">
            <div class="position-relative">
                <input type="text" id="nav_filter_input" placeholder="Filter menu" class="form-control"
                    tabindex="0">
                <a href="#" onclick="return false;" class="btn-primary btn-search-close js-waves-off"
                    data-action="toggle" data-class="list-filter-active" data-target=".page-sidebar">
                    <i class="fal fa-chevron-up"></i>
                </a>
            </div>
        </div>
        <div class="info-card">
            @if (auth()->user()->employee->foto != null && Storage::exists('employee/profile/' . auth()->user()->employee->foto))
                <img src="{{ asset('storage/employee/profile/' . auth()->user()->employee->foto) }}"
                    class="rounded-circle img-thumbnail" alt=""
                    style="width: 55px; height: 55px; object-fit: cover; z-index: 100;">
            @else
                <img src="{{ auth()->user()->employee->gender == 'Laki-laki' ? '/img/demo/avatars/avatar-c.png' : '/img/demo/avatars/avatar-p.png' }}"
                    class="rounded-circle img-thumbnail" alt="" style="width: 55px; z-index: 100;">
            @endif
            <div class="info-card-text">
                <a href="#" class="d-flex align-items-center text-white">
                    <span class="text-truncate text-truncate-sm d-inline-block">
                        {{ auth()->user()->name }}
                    </span>
                </a>
                <span class="d-inline-block text-truncate text-truncate-sm">Majalengka, Jawa Barat</span>
            </div>
            <img src="/img/card-backgrounds/cover-2-lg.png" class="cover" alt="cover">
            <a href="#" onclick="return false;" class="pull-trigger-btn" data-action="toggle"
                data-class="list-filter-active" data-target=".page-sidebar" data-focus="nav_filter_input">
                <i class="fal fa-angle-down"></i>
            </a>
        </div>

        <!-- Sidebar.blade.php -->
        <ul id="js-nav-menu" class="nav-menu">
            @php
                $appType = session('app_type', 'hr'); // Default ke 'hr' jika tidak ada session
            @endphp
            @if (auth()->user()->hasRole('super admin'))
                @foreach (App\Models\Menu::where('type', $appType)->whereNull('parent_id')->with('children.children')->orderBy('sort_order')->get() as $menu)
                    @include('inc.partials.menu', ['menu' => $menu])
                @endforeach
            @else
                @foreach (App\Models\Menu::where('type', $appType)->whereNull('parent_id')->with('children.children')->orderBy('sort_order')->get() as $menu)
                    @can($menu->permission)
                        @include('inc.partials.menu', ['menu' => $menu])
                    @endcan
                @endforeach
            @endif
        </ul>

        <div class="filter-message js-filter-message bg-success-600"></div>
    </nav>
    <!-- END PRIMARY NAVIGATION -->
    <!-- NAV FOOTER -->
    <div class="nav-footer shadow-top">
        <a href="#" onclick="return false;" data-action="toggle" data-class="nav-function-minify"
            class="hidden-md-down">
            <i class="ni ni-chevron-right"></i>
            <i class="ni ni-chevron-right"></i>
        </a>
        <ul class="list-table m-auto nav-footer-buttons">
            <li>
                <a href="/chatify" data-toggle="tooltip" data-placement="top" title="Chat logs">
                    <i class="fal fa-comments"></i>
                </a>
            </li>
            <li>
                <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Support Chat">
                    <i class="fal fa-life-ring"></i>
                </a>
            </li>
            <li>
                <a href="javascript:void(0);" data-toggle="tooltip" data-placement="top" title="Make a call">
                    <i class="fal fa-phone"></i>
                </a>
            </li>
        </ul>
    </div> <!-- END NAV FOOTER -->
</aside>
<!-- END Left Aside -->
