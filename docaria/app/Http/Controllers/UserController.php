<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB; 
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
     public function create(){
        return view('users.add');

    }

    public function edit($id){
        $user = User::findorFail($id);
        return view('users.edit', compact('user'));

    }


    public function store(Request $request){
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'role' => 'required|in:admin,vendedor,trabalhador',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|max:255',
        ]);

        DB::table('users')->insert([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('users.index')->with('success', 'Utilizador criado com sucesso');
    }



    public function index(Request $request){

     $users = User::paginate(10);;
       
        return view('users.index', compact('users'));

    }



    public function update(Request $request, $id){
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'role' => 'required|in:admin,vendedor,trabalhador',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|max:255',
        ]);

        $user = User::findorFail($id);
        $user->name = $validated['name'];
        $user->role = $validated['role'];
        $user->email = $validated['email'];

        // Só atualiza a password se o campo for preenchido.
        if (!empty($validated['password'])) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Utilizador actualizado com sucesso');
    }




    public function destroy($id){

    $user = User::findorFail($id);
    $user->delete();
    return redirect()->route('users.index')->with('success', 'Utilizador eliminado com sucesso');
    }

}
