<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create()
    {
        return view('users.add');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('users.edit', compact('user'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'role' => 'required|in:admin,vendedor,trabalhador',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:8|max:255',
        ]);

        DB::table('users')->insert([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'username' => $validated['username'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.index')->with('success', 'Utilizador criado com sucesso');
    }

    public function index(Request $request)
    {
        $users = User::paginate(10);

        return view('users.index', compact('users'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'role' => 'required|in:admin,vendedor,trabalhador',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'password' => 'nullable|string|min:8|max:255',
        ]);

        $user = User::findOrFail($id);
        $user->name = $validated['name'];
        $user->role = $validated['role'];
        $user->username = $validated['username'];

        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Utilizador actualizado com sucesso');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilizador eliminado com sucesso');
    }
}
