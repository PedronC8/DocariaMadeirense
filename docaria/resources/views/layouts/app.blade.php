<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="A Docaria - Sistema de Gestão">
    <meta name="author" content="A Docaria">
    
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="{{ asset('img/icons/icon-48x48.png') }}" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/icons-feather.html" />
    
    <title>@yield('title', 'A Docaria - Gestão')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />
    <link rel="canonical" href="https://demo-basic.adminkit.io/icons-feather.html" />
    
    @stack('styles')
</head>

<body>
    <div class="wrapper">
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="{{ route('orders.index') }}">
                    <img src="{{ asset('assets/logo_png.png') }}" alt="A Docaria" class="sidebar-brand-logo">
                </a>

                <ul class="sidebar-nav">
                    <li class="sidebar-item {{ request()->routeIs('orders.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('orders.index') }}">
                            <i class="align-middle" data-feather="shopping-cart"></i> 
                            <span class="align-middle">Encomendas</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('products.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('products.index') }}">
                            <i class="align-middle" data-feather="package"></i> 
                            <span class="align-middle">Produtos</span>
                        </a>
                    </li>

                    <li class="sidebar-item {{ request()->routeIs('clients.*') ? 'active' : '' }}">
                        <a class="sidebar-link" href="{{ route('clients.index') }}">
                            <i class="align-middle" data-feather="users"></i> 
                            <span class="align-middle">Clientes</span>
                        </a>
                    </li>

                    @if(Auth::check() && Auth::user()->role === 'admin')
                        <li class="sidebar-item {{ request()->routeIs('statistics') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('statistics') }}">
                                <i class="align-middle" data-feather="bar-chart-2"></i>
                                <span class="align-middle">Estatísticas</span>
                            </a>
                        </li>

                        <li class="sidebar-item {{ request()->routeIs('users.*') ? 'active' : '' }}">
                            <a class="sidebar-link" href="{{ route('users.index') }}">
                                <i class="align-middle" data-feather="settings"></i> 
                                <span class="align-middle">Administração</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </nav>
        
        <div class="main">
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>

                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">
                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                                <i class="align-middle" data-feather="settings"></i>
                            </a>

                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block user-menu-trigger" href="#" data-bs-toggle="dropdown">
                                <span class="user-display-name">{{ Auth::user()->name }}</span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        <i class="align-middle me-1" data-feather="log-out"></i> Sair
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="content">
                <div class="container-fluid p-0">
                    @yield('breadcrumb')
                    
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Sucesso!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Erro!</strong> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Atenção!</strong> Corrija os erros abaixo:
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @yield('content')
                </div>
            </main>

        </div>
    </div>

    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <script>
    feather.replace()
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        feather.replace();

        const sidebarToggle = document.querySelector('.sidebar-toggle');
        const sidebar = document.querySelector('.sidebar');
        
        if (sidebarToggle && sidebar) {
            sidebarToggle.addEventListener('click', function(e) {
                e.preventDefault();
                sidebar.classList.toggle('collapsed');
            });
        }

        if (window.innerWidth < 992) {
            document.addEventListener('click', function(e) {
                if (!sidebar.contains(e.target) && !sidebarToggle.contains(e.target)) {
                    sidebar.classList.add('collapsed');
                }
            });
        }
    });
    </script>

    @stack('scripts')
</body>

</html>
