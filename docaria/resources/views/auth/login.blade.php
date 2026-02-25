<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Login - A Docaria">
    <meta name="author" content="A Docaria">
    <title>Login - A Docaria</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <div class="text-center mb-3">
                                    <img src="{{ asset('assets/logo_png.png') }}" alt="Logo A Docaria" class="auth-logo">
                                </div>
                                <div class="m-sm-3">
                                    <form method="post" action="{{ route('login.store') }}" autocomplete="off">
                                        @csrf
                                        @if (session('status'))
                                            <div class="alert alert-success" role="alert">
                                                {{ session('status') }}
                                            </div>
                                        @endif
                                        @if ($errors->any())
                                            <div class="alert alert-danger" role="alert">
                                                {{ $errors->first() }}
                                            </div>
                                        @endif
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input class="form-control form-control-lg" type="email" name="email" value="{{ old('email') }}" placeholder="Digite o seu email" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Palavra-passe</label>
                                            <input class="form-control form-control-lg" type="password" name="password" placeholder="Digite a sua palavra-passe" required>
                                        </div>
                                        <div class="mb-3">
                                            <div class="form-check align-items-center">
                                                <input id="remember-me" type="checkbox" class="form-check-input" value="1" name="remember">
                                                <label class="form-check-label text-small" for="remember-me">Lembrar-me</label>
                                            </div>
                                        </div>
                                        <div class="d-grid gap-2 mt-3">
                                            <button type="submit" class="btn btn-lg btn-primary btn-login-submit">Entrar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        @if (Route::has('password.request'))
                            <div class="text-center mt-3">
                                <a href="{{ route('password.request') }}">Esqueceu-se da palavra-passe?</a>
                            </div>
                        @endif

                        @if (Route::has('register'))
                            <div class="text-center mt-3 mb-3">
                                <a href="{{ route('register') }}" class="auth-link-primary">Registar nova conta</a>
                            </div>
                        @else
                            <div class="text-center mt-3 mb-3">
                                A 2026 A Doçaria - Todos os direitos reservados
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>
</html>
