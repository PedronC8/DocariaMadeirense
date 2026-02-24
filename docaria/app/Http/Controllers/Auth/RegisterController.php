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
                'email' => ['required', 'email', 'max:255', 'unique:users,email'],
                'password' => ['required', 'string', 'min:8'],
            ],
            [
                'name.required' => 'O nome é obrigatório.',
                'name.max' => 'O nome não pode ter mais de 255 caracteres.',
                'email.required' => 'O email é obrigatório.',
                'email.email' => 'O email deve ser válido.',
                'email.max' => 'O email não pode ter mais de 255 caracteres.',
                'email.unique' => 'Este email já está registado.',
                'password.required' => 'A palavra-passe é obrigatória.',
                'password.min' => 'A palavra-passe deve ter pelo menos 8 caracteres.',
            ]
        );

        User::create($data);

        return redirect()
            ->route('login')
            ->with('status', 'Conta criada com sucesso. Faça login.');
    }
}
