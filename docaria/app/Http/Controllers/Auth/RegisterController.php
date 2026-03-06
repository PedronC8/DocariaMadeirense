<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function show()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $data = $request->validate(
            [
                'name' => ['required', 'string', 'max:255'],
                'username' => ['required', 'string', 'max:255', 'unique:users,username'],
                'password' => ['required', 'string', 'min:8'],
            ],
            [
                'name.required' => 'O nome e obrigatorio.',
                'name.max' => 'O nome nao pode ter mais de 255 caracteres.',
                'username.required' => 'O nome de utilizador e obrigatorio.',
                'username.max' => 'O nome de utilizador nao pode ter mais de 255 caracteres.',
                'username.unique' => 'Este nome de utilizador ja esta registado.',
                'password.required' => 'A palavra-passe e obrigatoria.',
                'password.min' => 'A palavra-passe deve ter pelo menos 8 caracteres.',
            ]
        );

        User::create($data);

        return redirect()
            ->route('login')
            ->with('status', 'Conta criada com sucesso. Faca login.');
    }
}
