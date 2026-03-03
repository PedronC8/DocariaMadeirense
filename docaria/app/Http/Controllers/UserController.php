<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB; 


class UserController extends Controller
{
    public function create() {
    $roles = \App\Models\User::distinct()->pluck('role'); // pega todos os roles únicos
    return view('users.add', compact('roles'));
}

    public function edit($id) {
    $user = \App\Models\User::findOrFail($id);
    $roles = \App\Models\User::distinct()->pluck('role'); // para preencher o select
    return view('users.edit', compact('user', 'roles'));
}


    public function store(Request $request){
$request->validate([
    'name' => 'required|string|max:50',
    'role' => 'nullable|string|max:20',
    'email' => 'nullable|string|max:20',
    'password' => 'nullable|string|max:255',
]);

DB::table('users')->insert([
    'name' => $request->input('name'),
    'role' => $request->input('role'),
    'email' => $request->input('email'),
    'password' => $request->input('password')
]);

return redirect()->route('users.index')->with('success', 'Utilizador criado com sucesso');
    }



    public function index(Request $request){

     $users = User::paginate(10);;
       
        return view('users.index', compact('users'));

    }



    public function update(Request $request, $id){

    $request->validate([
    'name' => 'sometimes|required|string|max:50',
    'role' => 'sometimes|required|string|max:50',
    'email' => 'nullable|string|max:20',
    'password' => 'nullable|string|max:20',
    
    ]);

  $user = User::findorFail($id);
  $user->name = $request->input('name');
  $user->email = $request->input('email');
  $user->role = $request->input('role');
  $user->password = $request->input('password');
  $user->save();

  return redirect()->route('users.index')->with('success', 'Utilizador actualizado com sucesso');
    }




    public function destroy($id){

    $user = User::findorFail($id);
    $user->delete();
    return redirect()->route('users.index')->with('success', 'Utilizador eliminado com sucesso');
    }



    public function show($id){
        $user = User::findOrFail($id)
        ->findOrFail($id);
        return view('users.show', compact('user'));

    }
}
