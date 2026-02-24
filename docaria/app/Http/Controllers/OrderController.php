<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Client;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['client', 'vendedor', 'trabalhador'])
        ->orderBy('order_date', 'desc'); 

        // Filtros
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        if ($request->filled('payment_status')) {
            $query->paymentStatus($request->payment_status);
        }

        if ($request->filled('client_id')) {
            $query->client($request->client_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->paginate(15);
        $clients = Client::orderBy('name')->get();

        return view('orders.index', compact('orders', 'clients'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $products = Product::active()
            ->with('subcategory.category')
            ->orderBy('name')
            ->get();
        $users = User::where('role', 'trabalhador')->get();

        return view('orders.create', compact('clients', 'products', 'users'));
    }

    /**
     * Store a newly created order
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'order_date' => 'required|date',
            'ready_date' => 'required|date|after_or_equal:order_date',
            'delivery_date' => 'required|date|after_or_equal:ready_date',
            'desired_date' => 'nullable|date',
            'status' => 'required|in:preparacao,concluido,entregue',
            'payment_status' => 'required|in:nao_pago,pago,parcial',
            'payment_method' => 'nullable|in:cartao,dinheiro,cheque',
            'trabalhador_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Calcular totais
            $subtotal = 0;
            foreach ($request->products as $item) {
                $product = Product::find($item['id']);
                $subtotal += $product->price * $item['quantity'];
            }

            $iva = $subtotal * 0.23; // 23% IVA
            $total = $subtotal + $iva;

            // Criar encomenda
            $order = Order::create([
                'client_id' => $validated['client_id'],
                'vendedor_id' => Auth::id(),
                'trabalhador_id' => $validated['trabalhador_id'],
                'order_date' => $validated['order_date'],
                'ready_date' => $validated['ready_date'],
                'delivery_date' => $validated['delivery_date'],
                'desired_date' => $validated['desired_date'],
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $total,
                'status' => $validated['status'],
                'payment_status' => $validated['payment_status'],
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'],
            ]);

            // Criar itens da encomenda
            foreach ($request->products as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Encomenda criada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erro ao criar encomenda: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified order
     */
    public function show(Order $order)
    {
        $order->load(['client', 'vendedor', 'trabalhador', 'items.product.subcategory.category']);
        
        return view('orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified order
     */
    public function edit(Order $order)
    {
        $clients = Client::orderBy('name')->get();
        $products = Product::active()
            ->with('subcategory.category')
            ->orderBy('name')
            ->get();
        $users = User::where('role', 'trabalhador')->get();
        
        $order->load('items.product');

        return view('orders.edit', compact('order', 'clients', 'products', 'users'));
    }

    /**
     * Update the specified order
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'client_id' => 'required|exists:clients,id',
            'order_date' => 'required|date',
            'ready_date' => 'required|date|after_or_equal:order_date',
            'delivery_date' => 'required|date|after_or_equal:ready_date',
            'desired_date' => 'nullable|date',
            'status' => 'required|in:preparacao,concluido,entregue',
            'payment_status' => 'required|in:nao_pago,pago,parcial',
            'payment_method' => 'nullable|in:cartao,dinheiro,cheque',
            'trabalhador_id' => 'nullable|exists:users,id',
            'notes' => 'nullable|string',
            'products' => 'required|array|min:1',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            DB::beginTransaction();

            // Calcular totais
            $subtotal = 0;
            foreach ($request->products as $item) {
                $product = Product::find($item['id']);
                $subtotal += $product->price * $item['quantity'];
            }

            $iva = $subtotal * 0.23; // 23% IVA
            $total = $subtotal + $iva;

            // Atualizar encomenda
            $order->update([
                'client_id' => $validated['client_id'],
                'trabalhador_id' => $validated['trabalhador_id'],
                'order_date' => $validated['order_date'],
                'ready_date' => $validated['ready_date'],
                'delivery_date' => $validated['delivery_date'],
                'desired_date' => $validated['desired_date'],
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $total,
                'status' => $validated['status'],
                'payment_status' => $validated['payment_status'],
                'payment_method' => $validated['payment_method'],
                'notes' => $validated['notes'],
            ]);

            // Remover itens antigos e criar novos
            $order->items()->delete();
            
            foreach ($request->products as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            DB::commit();

            return redirect()
                ->route('orders.show', $order)
                ->with('success', 'Encomenda atualizada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar encomenda: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified order
     */
    public function destroy(Order $order)
    {
        try {
            DB::beginTransaction();

            // Remover itens primeiro (foreign key)
            $order->items()->delete();
            
            // Remover encomenda
            $order->delete();

            DB::commit();

            return redirect()
                ->route('orders.index')
                ->with('success', 'Encomenda eliminada com sucesso!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->with('error', 'Erro ao eliminar encomenda: ' . $e->getMessage());
        }
    }
}
