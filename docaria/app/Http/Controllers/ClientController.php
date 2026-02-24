<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use Illuminate\Support\Facades\DB; 


class ClientController extends Controller

{
    public function create(){
        return view('clients.add');

    }

    public function edit($id){
        $client = Client::findorFail($id);
        return view('clients.edit', compact('client'));

    }

    public function store(Request $request){
$request->validate([
    'name' => 'required|string|max:50',
    'nif' => 'nullable|string|max:20',
    'contact' => 'nullable|string|max:20',
    'address' => 'nullable|string|max:255',
]);

DB::table('clients')->insert([
    'name' => $request->input('name'),
    'contact' => $request->input('contact'),
]);

return redirect()->route('clients.index')->with('success', 'Client created successfully');
    }

    public function index(){
        $clients = Client::paginate(10);
        return view('clients.index', compact('clients'));

    }

    public function update(Request $request, $id){

    $request->validate([
    'name' => 'sometimes|required|string|max:50',
    'nif' => 'nullable|string|max:20',
    'contact' => 'nullable|string|max:20',
    'address' => 'nullable|string|max:255',
]);

$client = Client::findorFail($id);
$client->name = $request->input('name');
$client->nif = $request->input('nif');
$client->contact = $request->input('contact');
$client->address = $request->input('address');
$client->save();
    }

    public function destroy($id){

    $client = Client::findorFail($id);
    $client->delete();
    return redirect()->route('clients.index')->with('success', 'Client deleted successfully');
    }

    public function show($id){
        $client = Client::findorFail($id);
        return view('clients.ver_cliente_id', compact('client'));

    }
}
