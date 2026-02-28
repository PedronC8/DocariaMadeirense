<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Order;
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

return redirect()->route('clients.index')->with('success', 'Cliente criado com sucesso');
    }



    public function index(Request $request)
{
    $query = Client::withCount('orders')
        ->withMax('orders', 'order_date');

    // filtro simples
    if ($request->search) {

        $query->where(function($q) use ($request) {

            $q->where('name', 'like', '%' . $request->search . '%')
              ->orWhere('contact', 'like', '%' . $request->search . '%')
              ->orWhere('nif', 'like', '%' . $request->search . '%')
                ->orWhere('address', 'like', '%' . $request->search . '%');

        });

    }

    $clients = $query->paginate(10);

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

  return redirect()->route('clients.index')->with('success', 'Cliente actualizado com sucesso');
    }



    public function destroy($id){

    $client = Client::findorFail($id);
    $client->delete();
    return redirect()->route('clients.index')->with('success', 'Cliente eliminado com sucesso');
    }

    public function show($id){
        $client = Client::withCount('orders')
        ->withMax('orders', 'order_date')
        ->findOrFail($id);
        return view('clients.ver_cliente_id', compact('client'));

    }
}
