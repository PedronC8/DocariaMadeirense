<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <title>Login - A Docaria</title>
    
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">

                        <div class="text-center mt-4">
                            <h1 class="h2">A Docaria</h1>
                            <p class="lead">Entre com as suas credenciais</p>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-3">
                                    @if (session('status'))
                                        <div class="alert alert-success">
                                            {{ session('status') }}
                                        </div>
                                    @endif

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            @foreach ($errors->all() as $error)
                                                {{ $error }}
                                            @endforeach
                                        </div>
                                    @endif

                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input class="form-control form-control-lg" 
                                                   type="email" 
                                                   name="email" 
                                                   placeholder="admin@adocaria.pt" 
                                                   value="{{ old('email') }}"
                                                   required 
                                                   autofocus />
                                        </div>
                                        
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <input class="form-control form-control-lg" 
                                                   type="password" 
                                                   name="password" 
                                                   placeholder="Introduza a sua password"
                                                   required />
                                        </div>
                                        
                                        <div class="mb-3">
                                            <div class="form-check align-items-center">
                                                <input id="remember" 
                                                       type="checkbox" 
                                                       class="form-check-input" 
                                                       name="remember">
                                                <label class="form-check-label text-small" for="remember">
                                                    Lembrar-me
                                                </label>
                                            </div>
                                        </div>
                                        
                                        <div class="d-grid gap-2 mt-3">
                                            <button type="submit" class="btn btn-lg btn-primary">
                                                Entrar
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center mb-3">
                            <small class="text-muted">
                                A Docaria &copy; {{ date('Y') }}
                            </small>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
