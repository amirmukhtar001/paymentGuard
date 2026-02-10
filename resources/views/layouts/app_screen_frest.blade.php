@php
    // Get theme configuration
    $configData = \App\Helpers\Helpers::appClasses();

    if (!isset($user)) {
        $user = \Illuminate\Support\Facades\Auth::user();
    }

    // Set layout classes based on config
    $navbarFixed = ($configData['navbarFixed'] ?? true) ? 'layout-navbar-fixed' : '';
    $menuFixed = ($configData['menuFixed'] ?? true) ? 'layout-menu-fixed' : '';
    $menuCollapsed = ($configData['menuCollapsed'] ?? false) ? 'layout-menu-collapsed' : '';
    $rtlSupport = $configData['rtlSupport'] ?? '/rtl';
    $rtlSupport = $rtlSupport ? '/rtl' : '';
@endphp
<!DOCTYPE html>

<html lang="en" class="{{ $configData['style'] }}-style {{ $navbarFixed }} {{ $menuFixed }} {{ $menuCollapsed }}"
    dir="{{ $configData['textDirection'] ?? 'ltr' }}"
    data-theme="{{ (($configData['theme'] === 'theme-semi-dark') ? (($configData['layout'] !== 'horizontal') ? $configData['theme'] : 'theme-default') : $configData['theme']) }}"
    data-assets-path="{{ asset('assets/') }}" data-base-url="{{ url('/') }}"
    data-template="{{ $configData['layout'] ?? 'vertical' }}-menu-{{ $configData['theme'] ?? 'theme-default' }}-{{ $configData['style'] ?? 'light' }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />

    @if (isset($ftitle))
        <title>{{ env('APP_ABBR') }} - {{ $ftitle ?? '' }}</title>
    @else
        <title>{{ env('APP_ABBR') }} - {{ $title ?? '' }}</title>
    @endif


    <meta name="description" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/favicon.ico') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
        href="https://fonts.googleapis.com/css2?family=IBM+Plex+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&family=Rubik:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
        rel="stylesheet" />

    <!-- Icons -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/fontawesome.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/flag-icons.css') }}" />
    <!-- Core CSS -->
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/css' . $rtlSupport . '/core' . ($configData['style'] !== 'light' ? '-' . $configData['style'] : '') . '.css') }}"
        class="{{ $configData['hasCustomizer'] ? 'template-customizer-core-css' : '' }}" />
    <link rel="stylesheet"
        href="{{ asset('assets/vendor/css' . $rtlSupport . '/' . $configData['theme'] . ($configData['style'] !== 'light' ? '-' . $configData['style'] : '') . '.css') }}"
        class="{{ $configData['hasCustomizer'] ? 'template-customizer-theme-css' : '' }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/typeahead-js/typeahead.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <!-- Page CSS -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-icons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/my_custom.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <style>
        .menu-item {
            margin-bottom: 6px;
            background: #ffffff;
            border-radius: 6px;
            transition: box-shadow 0.2s ease, transform 0.15s ease, background 0.2s ease;
            border: 1px solid #d0e2f3;
            position: relative;
            overflow: hidden;
        }

        .menu-item:hover {
            box-shadow: 0 6px 15px rgba(15, 23, 42, 0.1);
            transform: translateY(-1px);
        }

        /* Depth indicator line on the left like WP */
        .menu-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 3px;
            background: linear-gradient(180deg, #3b82f6, #0ea5e9);
            opacity: 0;
            transition: opacity 0.2s ease;
        }

        .menu-item.menu-item-active::before {
            opacity: 1;
        }
    </style>
    @stack('stylesheets')

    @livewireStyles

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>

    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
    @if ($configData['hasCustomizer'])
        <!--? Template customizer: To hide customizer set displayCustomizer value false in config.js.  -->
        <script src="{{ asset('assets/vendor/js/template-customizer.js') }}"></script>
    @endif
    <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
    <script src="{{ asset('assets/js/config.js') }}"></script>
    @if ($configData['hasCustomizer'])
        <script>
            // Initialize Template Customizer
            if (typeof TemplateCustomizer !== 'undefined') {
                window.templateCustomizer = new TemplateCustomizer({
                    cssPath: '',
                    themesPath: '',
                    defaultShowDropdownOnHover: {
                                                    {
                        $configData['showDropdownOnHover']? 'true' : 'false'
                                                    }
                                                }, // true/false (for horizontal layout only)
            displayCustomizer: {
                {
                    $configData['displayCustomizer'] ? 'true' : 'false'
                }
            },
            lang: '{{ app()->getLocale() }}',
                pathResolver: function(path) {
                    var resolvedPaths = {
                        // Core stylesheets
                        @foreach(['core'] as $name)
                                                                                        '{{ $name }}.css': '{{ asset("assets/vendor/css{$rtlSupport}/{$name}.css") }}',
                            '{{ $name }}-dark.css': '{{ asset("assets/vendor/css{$rtlSupport}/{$name}-dark.css") }}',
                        @endforeach

                    // Themes
                    @foreach(['default', 'bordered', 'semi-dark'] as $name)
                        'theme-{{ $name }}.css': '{{ asset("assets/vendor/css{$rtlSupport}/theme-{$name}.css") }}',
                            'theme-{{ $name }}-dark.css': '{{ asset("assets/vendor/css{$rtlSupport}/theme-{$name}-dark.css") }}',
                    @endforeach
                                                    }
            return resolvedPaths[path] || path;
                                                },
            'controls': <?php    echo json_encode($configData['customizerControls']); ?>,
                                            });
                                        }
        </script>
    @endif



    @stack('scripts-top')
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->


            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="{{ route('home') }}" class="app-brand-link">
                        <span class="app-brand-text demo menu-text fw-bold ms-2">
                            <!-- <img src="{{ site_logo() }}" width="150"> -->
                            <img src="{{ asset('assets/img/kp_logo.png') }}" width="60">
                        </span>
                    </a>

                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-none">
                        <i class="bx menu-toggle-icon d-none d-xl-block fs-4 align-middle"></i>
                        <i class="bx bx-x d-block d-xl-none bx-sm align-middle"></i>
                    </a>
                </div>

                <div class="menu-divider mt-0"></div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    @php
                        use Illuminate\Support\Facades\DB;

                        $user_role_ids = [0];
                        foreach ($user->roles as $ur) {
                            $user_role_ids[] = $ur->id;
                        }
                        // // menu of this app assigned to this user and roles
                        // $menu_r = \App\Models\Menu::with([
                        // 'myPermissions' => function ($q) use ($user_role_ids, $user) {
                        // $q->with([
                        // 'routes' => function ($q) use ($user_role_ids, $user) {
                        // $q->where('is_default', '=', 'yes'); },])->where('show_in_menu', '=', 'yes')
                        // ->whereRaw("id in (SELECT permission_id FROM permission_role WHERE role_id in (" . implode(',', $user_role_ids) ."))",)
                        // ->orWhereRaw("id in (SELECT permission_id FROM permission_user WHERE user_id in (" .$user->id ."))",);
                        // },
                        // ])->whereHas('myPermissions', function ($q) use ($user_role_ids, $user) {
                        // $q->whereRaw("id in (SELECT permission_id FROM permission_role WHERE role_id in (" . implode(',', $user_role_ids) ."))",
                        // )->orWhereRaw("id in (SELECT permission_id FROM permission_user WHERE user_id in (" . $user->id .") )", ); })->get();
                        // // dd($menu_r->toSql());

                        $menu_r = \App\Models\Menu::with([
                            'myPermissions' => function ($q) use ($user_role_ids, $user) {
                                $q->where('show_in_menu', 'yes')
                                    ->where(function ($query) use ($user_role_ids, $user) {
                                        $query->whereHas('roles', function ($q) use ($user_role_ids) {
                                            $q->whereIn('role_id', $user_role_ids);
                                        })
                                            ->orWhereHas('users', function ($q) use ($user) {
                                            $q->where('user_id', $user->id);
                                        });
                                    })
                                    ->with(['routes' => fn($q) => $q->where('is_default', 'yes')]);
                            },
                            'childrenRecursive' => function ($q) use ($user_role_ids, $user) {
                                $q->whereHas('myPermissions', function ($q2) use ($user_role_ids, $user) {
                                    $q2->where(function ($query) use ($user_role_ids, $user) {
                                        $query->whereHas('roles', function ($q) use ($user_role_ids) {
                                            $q->whereIn('role_id', $user_role_ids);
                                        })
                                        ->orWhereHas('users', function ($q) use ($user) {
                                            $q->where('user_id', $user->id);
                                        });
                                    });
                                })
                                // Or has descendants with permissions
                                ->orWhereHas('childrenRecursive.myPermissions', function ($q2) use ($user_role_ids, $user) {
                                    $q2->where(function ($query) use ($user_role_ids, $user) {
                                        $query->whereHas('roles', function ($q) use ($user_role_ids) {
                                            $q->whereIn('role_id', $user_role_ids);
                                        })
                                        ->orWhereHas('users', function ($q) use ($user) {
                                            $q->where('user_id', $user->id);
                                        });
                                    });
                                })
                                ->orderBy('order');
                            },
                        ])
                            ->whereNull('parent_id')
                            ->where(function ($query) use ($user_role_ids, $user) {
                                $query->whereHas('myPermissions', function ($q) use ($user_role_ids, $user) {
                                    $q->where(function ($query) use ($user_role_ids, $user) {
                                        $query->whereHas('roles', function ($q) use ($user_role_ids) {
                                            $q->whereIn('role_id', $user_role_ids);
                                        })
                                            ->orWhereHas('users', function ($q) use ($user) {
                                            $q->where('user_id', $user->id);
                                        });
                                    });
                                })
                                ->orWhereHas('childrenRecursive.myPermissions', function ($q) use ($user_role_ids, $user) {
                                    $q->where(function ($query) use ($user_role_ids, $user) {
                                        $query->whereHas('roles', function ($q) use ($user_role_ids) {
                                            $q->whereIn('role_id', $user_role_ids);
                                        })
                                        ->orWhereHas('users', function ($q) use ($user) {
                                            $q->where('user_id', $user->id);
                                        });
                                    });
                                });
                            })
                            ->orderBy('order')
                            ->get();

                    @endphp


                    {{-- <li class="menu-item">
                        <a href="{{ route('home') }}" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home"></i>
                            <span>Home</span>
                        </a>
                    </li>

                    <li class="menu-item">
                        <?php //echo url()->previous();
                        ?>
                        <a href="{{ url('/') }}" class="menu-link active">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <span data-i18n="Dashboards">Dashboard</span>
                        </a>
                    </li> --}}



                    @php
                        if (!function_exists('renderSidebarMenuRecursive')) {
                            function renderSidebarMenuRecursive($menu, $level = 0) {
                                $html = '';
                                $hasActiveChild = false;
                                
                                // Buffer permissions HTML
                                $permissionsHtml = '';
                                foreach ($menu->myPermissions as $mp) {
                                    foreach ($mp->routes as $mpr) {
                                        if ($mp->show_in_menu == 'no') continue;
                                        if ($mpr->is_default !== 'yes') continue;
                                        try {
                                            $routeExists = \Illuminate\Support\Facades\Route::has($mpr->route);
                                        } catch (\Exception $e) { $routeExists = false; }
                                        if (!$routeExists) continue;

                                        $activeClass = (\Illuminate\Support\Facades\Route::currentRouteName() == $mpr->route) ? 'active' : '';
                                        if ($activeClass) $hasActiveChild = true;

                                        $permissionsHtml .= '<li class="menu-item ' . $activeClass . '">';
                                        $permissionsHtml .= '<a href="' . route($mpr->route) . '" class="menu-link">' . e($mpr->title) . '</a>';
                                        $permissionsHtml .= '</li>';

                                        if ($activeClass) {
                                            $permissionsHtml .= '<script>$(document).ready(function(){ $("#men_item_' . $menu->id . '").addClass("open"); });</script>';
                                        }
                                    }
                                }

                                // Buffer children HTML
                                $childrenHtml = '';
                                $children = $menu->childrenRecursive ?? $menu->children;
                                if ($children && $children->count() > 0) {
                                    foreach ($children as $child) {
                                        $childResult = renderSidebarMenuRecursive($child, $level + 1);
                                        $childrenHtml .= $childResult['html'];
                                        if ($childResult['active']) {
                                            $hasActiveChild = true;
                                            $childrenHtml .= '<script>$(document).ready(function(){ $("#men_item_' . $menu->id . '").addClass("open"); });</script>';
                                        }
                                    }
                                }

                                if (empty($permissionsHtml) && empty($childrenHtml)) {
                                    return ['html' => '', 'active' => false];
                                }

                                $html .= '<li class="menu-item" id="men_item_' . $menu->id . '">';
                                $html .= '<a href="javascript:void(0);" class="menu-link menu-toggle">';
                                if ($menu->icon && $level == 0) {
                                    $html .= '<i class="menu-icon tf-icons ' . $menu->icon . '"></i>';
                                } elseif ($menu->icon) {
                                    $html .= '<i class="' . $menu->icon . '"></i>';
                                }
                                $html .= '<span>' . e($menu->title) . '</span>';
                                $html .= '</a>';
                                $html .= '<ul class="menu-sub" data-submenu-title="' . e($menu->title) . '">' . $permissionsHtml . $childrenHtml . '</ul>';
                                $html .= '</li>';

                                return ['html' => $html, 'active' => $hasActiveChild];
                            }
                        }
                    @endphp

                    @foreach ($menu_r as $menu)
                        @php
                            $renderResult = renderSidebarMenuRecursive($menu);
                        @endphp
                        {!! $renderResult['html'] !!}
                    @endforeach

                    <!-- Dashboards -->
                    {{-- <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Dashboards">Dashboards</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="index.html" class="menu-link">
                                    <div data-i18n="Analytics">Analytics</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="dashboards-ecommerce.html" class="menu-link">
                                    <div data-i18n="eCommerce">eCommerce</div>
                                </a>
                            </li>
                        </ul>
                    </li> --}}

                    <!-- Components -->
                    {{-- <li class="menu-header small text-uppercase"><span class="menu-header-text">Components</span>
                    </li> --}}

                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar navbar navbar-expand-xl align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="container-fluid">
                        <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                            <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                                <i class="bx bx-menu bx-sm"></i>
                            </a>
                        </div>

                        <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                            <div class="navbar-nav align-items-center">
                                <div class="nav-item mb-0">
                                    @auth
                                    <div class="d-flex align-items-center">
                                        <span class="text-dark me-3">
                                            <strong>{{ Auth::user()->name }}</strong>
                                        </span>
                                        @if(Auth::user()->company)
                                        <span class="text-dark">
                                            {{ Auth::user()->company->title }}
                                        </span>
                                        @endif
                                    </div>
                                    @endauth
                                </div>
                            </div>
                            <!-- User Name & Company -->

                            <ul class="navbar-nav flex-row align-items-center ms-auto">
                                {{--
                                <!-- Language -->
                                <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                        data-bs-toggle="dropdown">
                                        <i class="fi fi-us fis rounded-circle fs-3 me-1"></i>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" data-language="en">
                                                <i class="fi fi-us fis rounded-circle fs-4 me-1"></i>
                                                <span class="align-middle">English</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" data-language="fr">
                                                <i class="fi fi-fr fis rounded-circle fs-4 me-1"></i>
                                                <span class="align-middle">French</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" data-language="de">
                                                <i class="fi fi-de fis rounded-circle fs-4 me-1"></i>
                                                <span class="align-middle">German</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="javascript:void(0);" data-language="pt">
                                                <i class="fi fi-pt fis rounded-circle fs-4 me-1"></i>
                                                <span class="align-middle">Portuguese</span>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <!--/ Language -->
                                --}}

                                <!-- Style Switcher -->
                                @if ($configData['hasCustomizer'])
                                    <li class="nav-item me-2 me-xl-0">
                                        <a class="nav-link style-switcher-toggle hide-arrow" href="javascript:void(0);">
                                            <i class='bx bx-sm'></i>
                                        </a>
                                    </li>
                                    <!--/ Style Switcher -->
                                @endif

                                <!-- Quick links  -->
                                <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        <i class="bx bx-grid-alt bx-sm"></i>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end py-0">
                                        <div class="dropdown-menu-header border-bottom">
                                            <div class="dropdown-header d-flex align-items-center py-3">
                                                <h5 class="text-body mb-0 me-auto">Shortcuts</h5>
                                                <a href="javascript:void(0)" class="dropdown-shortcuts-add text-body"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Add shortcuts"><i class="bx bx-sm bx-plus-circle"></i></a>
                                            </div>
                                        </div>
                                        <div class="dropdown-shortcuts-list scrollable-container">
                                            <div class="row row-bordered overflow-visible g-0">
                                                <div class="dropdown-shortcuts-item col">
                                                    <span
                                                        class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                        <i class="bx bx-calendar fs-4"></i>
                                                    </span>
                                                    <a href="app-calendar.html" class="stretched-link">Calendar</a>
                                                    <small class="text-muted mb-0">Appointments</small>
                                                </div>
                                                <div class="dropdown-shortcuts-item col">
                                                    <span
                                                        class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                        <i class="bx bx-food-menu fs-4"></i>
                                                    </span>
                                                    <a href="app-invoice-list.html" class="stretched-link">Invoice
                                                        App</a>
                                                    <small class="text-muted mb-0">Manage Accounts</small>
                                                </div>
                                            </div>
                                            <div class="row row-bordered overflow-visible g-0">
                                                <div class="dropdown-shortcuts-item col">
                                                    <span
                                                        class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                        <i class="bx bx-user fs-4"></i>
                                                    </span>
                                                    <a href="app-user-list.html" class="stretched-link">User App</a>
                                                    <small class="text-muted mb-0">Manage Users</small>
                                                </div>
                                                <div class="dropdown-shortcuts-item col">
                                                    <span
                                                        class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                        <i class="bx bx-check-shield fs-4"></i>
                                                    </span>
                                                    <a href="app-access-roles.html" class="stretched-link">Role
                                                        Management</a>
                                                    <small class="text-muted mb-0">Permission</small>
                                                </div>
                                            </div>
                                            <div class="row row-bordered overflow-visible g-0">
                                                <div class="dropdown-shortcuts-item col">
                                                    <span
                                                        class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                        <i class="bx bx-pie-chart-alt-2 fs-4"></i>
                                                    </span>
                                                    <a href="{{route('home')}}" class="stretched-link">Dashboard</a>
                                                    <small class="text-muted mb-0">User Profile</small>
                                                </div>
                                                <div class="dropdown-shortcuts-item col">
                                                    <span
                                                        class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                        <i class="bx bx-cog fs-4"></i>
                                                    </span>
                                                    <a href="pages-account-settings-account.html"
                                                        class="stretched-link">Setting</a>
                                                    <small class="text-muted mb-0">Account Settings</small>
                                                </div>
                                            </div>
                                            <div class="row row-bordered overflow-visible g-0">
                                                <div class="dropdown-shortcuts-item col">
                                                    <span
                                                        class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                        <i class="bx bx-help-circle fs-4"></i>
                                                    </span>
                                                    <a href="pages-help-center-landing.html" class="stretched-link">Help
                                                        Center</a>
                                                    <small class="text-muted mb-0">FAQs & Articles</small>
                                                </div>
                                                <div class="dropdown-shortcuts-item col">
                                                    <span
                                                        class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">
                                                        <i class="bx bx-window-open fs-4"></i>
                                                    </span>
                                                    <a href="modal-examples.html" class="stretched-link">Modals</a>
                                                    <small class="text-muted mb-0">Useful Popups</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <!-- Quick links -->

                                <!-- Notification -->
                                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                        data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                        <i class="bx bx-bell bx-sm"></i>
                                        <span class="badge bg-danger rounded-pill badge-notifications">5</span>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end py-0">
                                        <li class="dropdown-menu-header border-bottom">
                                            <div class="dropdown-header d-flex align-items-center py-3">
                                                <h5 class="text-body mb-0 me-auto">Notification</h5>
                                                <a href="javascript:void(0)"
                                                    class="dropdown-notifications-all text-body"
                                                    data-bs-toggle="tooltip" data-bs-placement="top"
                                                    title="Mark all as read"><i
                                                        class="bx fs-4 bx-envelope-open"></i></a>
                                            </div>
                                        </li>
                                        <li class="dropdown-notifications-list scrollable-container">
                                            <ul class="list-group list-group-flush">
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <img src="{{ asset('assets/img/avatars/1.png') }}" alt
                                                                    class="w-px-40 h-auto rounded-circle" />
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">Congratulation Lettie 🎉</h6>
                                                            <p class="mb-0">Won the monthly best seller gold badge</p>
                                                            <small class="text-muted">1h ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <span
                                                                    class="avatar-initial rounded-circle bg-label-danger">CF</span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">Charles Franklin</h6>
                                                            <p class="mb-0">Accepted your connection</p>
                                                            <small class="text-muted">12hr ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <img src="{{ asset('assets/img/avatars/2.png') }}" alt
                                                                    class="w-px-40 h-auto rounded-circle" />
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">New Message ✉️</h6>
                                                            <p class="mb-0">You have new message from Natalie</p>
                                                            <small class="text-muted">1h ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <span
                                                                    class="avatar-initial rounded-circle bg-label-success"><i
                                                                        class="bx bx-cart"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">Whoo! You have new order 🛒</h6>
                                                            <p class="mb-0">ACME Inc. made new order $1,154</p>
                                                            <small class="text-muted">1 day ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <img src="{{ asset('assets/img/avatars/9.png') }}" alt
                                                                    class="w-px-40 h-auto rounded-circle" />
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">Application has been approved 🚀</h6>
                                                            <p class="mb-0">Your ABC project application has been
                                                                approved.</p>
                                                            <small class="text-muted">2 days ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <span
                                                                    class="avatar-initial rounded-circle bg-label-success"><i
                                                                        class="bx bx-pie-chart-alt"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">Monthly report is generated</h6>
                                                            <p class="mb-0">July monthly financial report is generated
                                                            </p>
                                                            <small class="text-muted">3 days ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <img src="{{ asset('assets/img/avatars/5.png') }}" alt
                                                                    class="w-px-40 h-auto rounded-circle" />
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">Send connection request</h6>
                                                            <p class="mb-0">Peter sent you connection request</p>
                                                            <small class="text-muted">4 days ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <img src="{{ asset('assets/img/avatars/6.png') }}" alt
                                                                    class="w-px-40 h-auto rounded-circle" />
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">New message from Jane</h6>
                                                            <p class="mb-0">Your have new message from Jane</p>
                                                            <small class="text-muted">5 days ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li
                                                    class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                                                    <div class="d-flex">
                                                        <div class="flex-shrink-0 me-3">
                                                            <div class="avatar">
                                                                <span
                                                                    class="avatar-initial rounded-circle bg-label-warning"><i
                                                                        class="bx bx-error"></i></span>
                                                            </div>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <h6 class="mb-1">CPU is running high</h6>
                                                            <p class="mb-0">CPU Utilization Percent is currently at
                                                                88.63%,</p>
                                                            <small class="text-muted">5 days ago</small>
                                                        </div>
                                                        <div class="flex-shrink-0 dropdown-notifications-actions">
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-read"><span
                                                                    class="badge badge-dot"></span></a>
                                                            <a href="javascript:void(0)"
                                                                class="dropdown-notifications-archive"><span
                                                                    class="bx bx-x"></span></a>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="dropdown-menu-footer border-top">
                                            <a href="javascript:void(0);"
                                                class="dropdown-item d-flex justify-content-center p-3">
                                                View all notifications
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <!--/ Notification -->

                                <!-- User -->
                                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                    <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);"
                                        data-bs-toggle="dropdown">
                                        <div class="avatar avatar-online">
                                            <i class="fa fa-user fa-2x"></i>
                                        </div>
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item" href="javascript:;">
                                                <div class="d-flex">
                                                    <div class="flex-shrink-0 me-3">
                                                        <div class="avatar avatar-online">
                                                            <div class="avatar avatar-online">
                                                                <i class="fa fa-user fa-2x"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex-grow-1">
                                                        <span
                                                            class="fw-semibold d-block lh-1">{{ auth()->user()->name }}</span>
                                                        {{-- <small>Admin</small> --}}
                                                    </div>
                                                </div>
                                            </a>
                                        </li>

                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('settings.users-mgt.my-profile') }}">
                                                <i class="bx bx-user me-2"></i>
                                                <span class="align-middle">My Profile</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item"
                                                href="{{ route('settings.users-mgt.change-password') }}">
                                                <i class="bx bx-cog me-2"></i>
                                                <span class="align-middle">Change Password</span>
                                            </a>
                                        </li>

                                        <li>
                                            <div class="dropdown-divider"></div>
                                        </li>


                                        <li>
                                            {{-- <a class="dropdown-item" href="auth-login-cover.html" target="_blank">
                                                <i class="bx bx-power-off me-2"></i>
                                                <span class="align-middle">Log Out</span>
                                            </a> --}}


                                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                <i class="bx bx-power-off me-2"></i>
                                                <span class="align-middle">{{ __('Logout') }}</span>
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                                class="d-none">
                                                @csrf
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                                <!--/ User -->
                            </ul>
                        </div>

                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->

                    <div class="container-xxl flex-grow-1 container-p-y">

                        <h5 class="py-3 breadcrumb-wrapper mb-4">
                            <span class="text-muted fw-light">
                                <a href="{{ route('app.landing-screen') }}"><i class="bx bx-home mr2"></i> Home</a> /
                            </span>

                            <span class="text-muted fw-light">
                                <a href="{{ url('/') }}">
                                    <i class="bx bx-home"></i>
                                    {{ config('settings.company_title', 'Portal') }}
                                </a> /
                            </span>

                            @if (isset($back_route))
                                @if (isset($back_route[2]))
                                    <span class="text-muted fw-light">
                                        <a href="{{ $back_route[0] }}">
                                            {{ $back_route[1] }}
                                        </a> /
                                    </span>
                                @else
                                    <span class="text-muted fw-light">
                                        <a href="{{ route($back_route[0]) }}">
                                            {{ $back_route[1] }}
                                        </a> /
                                    </span>
                                @endif

                            @endif

                            @if (isset($ftitle))
                                {{ $ftitle ?? '' }}
                            @else
                                {{ $title ?? '' }}
                            @endif



                        </h5>



                        <div class="row">
                            <div class="col-12">
                                @if (\Illuminate\Support\Facades\Session::has('error'))
                                    <div class="alert alert-solid-danger alert-dismissible" role="alert">
                                        <h6 class="alert-heading mb-1"><i
                                                class="bx bx-xs bx-desktop align-top me-2"></i>Error!</h6>
                                        <span>{{ \Illuminate\Support\Facades\Session::get('error') }}</span>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                @if (\Illuminate\Support\Facades\Session::has('success'))
                                    <div class="alert alert-solid-success alert-dismissible" role="alert">
                                        <h6 class="alert-heading mb-1"><i
                                                class="bx bx-xs bx-desktop align-top me-2"></i>Success!</h6>
                                        <span>{{ \Illuminate\Support\Facades\Session::get('success') }}</span>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                                @if ($errors->any())
                                    <div class="alert alert-solid-danger alert-dismissible" role="alert">
                                        <h6 class="alert-heading mb-1"><i
                                                class="bx bx-xs bx-desktop align-top me-2"></i>There were some problems with
                                            your submission:</h6>
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert"
                                            aria-label="Close"></button>
                                    </div>
                                @endif

                            </div>
                        </div>


                        @yield('content')
                        <?php
// Removed app_id check - footer widget removed
if (false) { ?>
                        <div class="footer-widget" style="z-index: 100; width:100%;padding-left:95%;">

                            <div class="demo-inline-spacing page-shortcut">
                                <div class="btn-group">
                                    <button type="button"
                                        class="btn btn-danger btn-icon rounded-pill btn-lg dropdown-toggle hide-arrow"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bx bx-plus bx-vertical-rounded" style="font-size:100%"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" style="">
                                        @if (isset($assignedMenus, $assignedMenus['eo.rni.index']))
                                            <li><a class="dropdown-item" href="{{ route('eo.rni.index') }}">RNI</a>
                                            </li>
                                        @endif
                                        @if (isset($assignedMenus, $assignedMenus['eo.e_letters.index']))
                                            <li><a class="dropdown-item"
                                                    href="{{ route('eo.e_letters.index') }}">e-Letter</a></li>
                                        @endif
                                        @if (isset($assignedMenus, $assignedMenus['eo.noting.list']))
                                            <li><a class="dropdown-item" href="{{ route('eo.noting.list') }}">e-Noting</a>
                                            </li>
                                        @endif
                                    </ul>
                                </div>
                            </div>


                        </div>
                        <?php } ?>

                    </div>

                    <!-- / Content -->

                    <!-- Footer -->
                    <!-- <footer class="content-footer footer bg-footer-theme bottom-border-line">
                        <div
                            class="container-fluid d-flex flex-wrap justify-content-center py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                <h3 class="text-center an-initiative-on">A Project by</h3>
                           <img src="{{ site_logo('bottom') }}" width="300" />
                                <img src="{{ asset('assets/img/itboardlogo.png') }}" width="900" />
                            </div>
                        </div>
                    </footer> -->
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>

        <!-- Drag Target Area To SlideIn Menu On Small Screens -->
        <div class="drag-target"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <!-- build:js assets/vendor/js/core.js -->
    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/hammer/hammer.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/i18n/i18n.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/typeahead-js/typeahead.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/pdfmake.min.js') }}"></script>
    <script src="{{ asset('assets/js/pdfmake-urdu-loader.js') }}"></script>
    {{--
    <script src="{{ asset('assets/js/pdfmake_urdu.js') }}"></script> --}}
    <!-- Main JS -->
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/vue.js') }}"></script>
    <script src="{{ asset('assets/js/laravel-file-uploader.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function () {
            $(".select2").select2();

            /**
             * To use this function you need to assign class select2-customer to the <select> elemet
             * and also define the data-placeholder attribute
             */
            if ($('.select2-custom').html() != undefined && $('.select2-custom').length > 0) {
                $('.select2-custom').each(function (index, value) {
                    renderSelect2(value);
                })
            }


            /**
             * On multiple selection remove the ALL value
             **/
            $('.select2-custom').on('select2:select', function (e) {
                var data = e.params.data;
                var id = e.target.id
                $(this).select2('destroy');
                if (data.id == 'ALL') {
                    $('#' + id).val(null).trigger('change');
                    $('#' + id).val(data.id).trigger('change');
                } else {
                    arr = $('#' + id).val()
                    arr = $.grep(arr, function (n) {
                        return n != 'ALL';
                    });
                    $('#' + id).val(arr).trigger('change');
                }
                $(this).select2();
            });

            $(document).find('.radio-custom :checked').each(function (i, v) {
                $(v).parents('.radio-custom').addClass('active');
            })

            $(document).on('change', '[type="radio"]', function () {
                console.log($(this).parents('.radio-container').html())
                $(this).parents('.radio-container').find('.radio-custom').each(function (i, v) {
                    $(v).removeClass('active');
                })
                $(this).parents('.radio-custom').addClass('active');
            })


        })

        /**
         * Show Select option in Ajax Success;
         * param will require data in this format: <select></select>
         */
        function renderSelect2(selector) {
            placeholder = $(selector).data('placeholder');
            if (placeholder == undefined) {
                placeholder = "Select an option"
            }
            if ($(selector).attr('multiple') != undefined) {
                $(selector).select2({
                    placeholder,
                    allowHtml: true,
                    allowClear: true,
                })
            } else {
                $(selector).select2({
                    placeholder,
                    allowClear: true
                })
            }
        }

        /**
         * Reset select2 before AjaxRequest
         * in params pass identifier name like id => cc_user_id
         */
        function resetSelect2(id) {
            $("select#" + id).select2("destroy");
            $("select#" + id).css("max-height", "2.3rem");
            $("#" + id).html("<option>Loading...</option>");
        }

        $(document).ready(function () {
            $(".delete").click(function (e) {
                return confirm('Are you sure you want to delete?')
            })
        })

        $(document).ready(function () {
            $.each($(".req"), function (i, j) {
                var label = $(this).html();
                $(this).html(label + " <span class='starik' style='color:red;font-size:11px;'>*</span>");
            })

        });
        if (document.getElementById('vueapp')) {
            new Vue({
                el: '#vueapp'
            })
        }


        // Set up CSRF token for all AJAX requests
        // 🗑️ AJAX Delete + SweetAlert
        $(document).on('click', '.delete_record', function (e) {
            e.preventDefault();

            let $btn = $(this);
            let url = $btn.data('url');
            let tableSelector = $btn.data('table'); // e.g. "#categories-table"

            Swal.fire({
                title: "Are you sure?",
                text: "This will permanently delete the record.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: url,
                        type: "POST",
                        data: {
                            _method: "DELETE", // This is important for sending a DELETE request
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (res) {
                            if (res.success) {
                                Swal.fire({
                                    title: "Deleted!",
                                    text: res.message ?? "Record deleted successfully.=",
                                    icon: "success"
                                });

                                // 🔁 Reload the correct DataTable if present
                                if (tableSelector && $.fn.DataTable.isDataTable(tableSelector)) {
                                    $(tableSelector).DataTable().ajax.reload(null, false);
                                }
                            } else {
                                Swal.fire({
                                    title: "Error!",
                                    text: res.message ?? "Unable to delete the record.",
                                    icon: "error"
                                });
                            }
                        },
                        error: function (xhr) {
                            // Handle the error response if AJAX fails
                            Swal.fire({
                                title: "Error!",
                                text: xhr.responseJSON?.message ?? "Unable to delete the record.",
                                icon: "error"
                            });
                        }
                    });
                }
            });
        });
    </script>
    @stack('scripts')

    @livewireScripts

    <!-- Page JS -->


</body>

</html>
