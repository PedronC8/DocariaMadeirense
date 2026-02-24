<form method="POST" action="/login">
    @csrf

    <input name="email" type="email" placeholder="Email">
    <input name="password" type="password" placeholder="Password">

    <button type="submit">Login</button>
</form>


