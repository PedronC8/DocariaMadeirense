<form method="POST" action="{{ route('register') }}">
    @csrf

    <input  name="type" placeholder="Tipo de utilizador">
    <input name="username" type="text" placeholder="Username">
    <input name="email" placeholder="Email" type="email">
    <input name="password" placeholder="Password" type="password">
    <input name="password_confirmation" placeholder="Confirmar Password" type="password">

    <button type="submit">Register</button>
</form>
