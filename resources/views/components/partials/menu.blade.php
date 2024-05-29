<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <x-nav-link :to="route('ADMIN_TOP')" class="brand-link text-center">
        <span class="brand-text font-weight-light">Intern PHP Source</span>
    </x-nav-link>

    <!-- Sidebar -->
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column"
                data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item">
                    @php
                        // menu items and their intended routes
                        $menuItems = [
                            'User' => 'ADMIN_USER_SEARCH',
                            'Product' => '#',
                        ];
                    @endphp
                    {{-- check the middleware of each route --}}
                    {{-- if it has the authorize user flag middleware then --}}
                    {{-- only show item if current user flag matches one of the supplied flags --}}
                    {{-- else show by default --}}
                    {{-- edge case: show if authorize user flag accept no flags, ie "auth_user_flg:" --}}
                    {{-- edge case: also show if route name is invalid --}}
                    @foreach ($menuItems as $label => $routeName)
                        @php
                            $shouldShow = false;
                            try {
                                // trycatch error-prone ops while prepping essentials
                                try {
                                    $url = route($routeName);
                                    $userFlag = request()->user()?->user_flg;
                                    $route = Route::getRoutes()->getRoutesByName()[
                                        $routeName
                                    ];
                                    $middlewares = $route?->middleware() ?? [];
                                    $authOnFlagsMiddleware = collect(
                                        array_filter($middlewares, function (
                                            $middleware,
                                        ) {
                                            return str_starts_with(
                                                $middleware,
                                                'authorize_user_flg',
                                            );
                                        }),
                                    )->first();
                                } catch (\Throwable $th) {
                                    $url = $routeName;
                                    $authOnFlagsMiddleware = 'authorize_user_flg:';
                                }
                                // at this point we have "authorize_user_flg:...", split the :, get the second half
                                $authorizedFlagsString = explode(
                                    ':',
                                    $authOnFlagsMiddleware,
                                )[1];
                                // if empty string aka accept no flags, show
                                if ($authorizedFlagsString === '') {
                                    $shouldShow = true;
                                } else {
                                    // split second half by ,
                                    $authorizedFlags = explode(
                                        ',',
                                        $authorizedFlagsString,
                                    );
                                    // convert user_flg to text
                                    $flagText = strtolower(
                                        getValueToText(
                                            $userFlag,
                                            'user.user_flg',
                                        ),
                                    );
                                    // match said text against accepted flags
                                    if (in_array($flagText, $authorizedFlags)) {
                                        $shouldShow = true;
                                    } else {
                                        $shouldShow = false;
                                    }
                                }
                            // fallback show by default
                            } catch (\Throwable $th) {
                                $shouldShow = true;
                            }
                        @endphp
                        @if ($shouldShow)
                        <x-nav-link to="{{ $url }}" class="nav-link"
                            :id="'nav-link-' . strtolower($label)">
                            <i class="nav-icon fas fa-file"></i>
                            <p>{{ $label }}</p>
                        </x-nav-link>
                        @endif
                    @endforeach
                </li>
            </ul>
        </nav>
    </div>
</aside>
