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
use Carbon\Carbon;

class OrderController extends Controller
{
    /**
     * Display a listing of orders
     */
    public function index(Request $request)
    {
        $query = Order::with(['client', 'vendedor', 'trabalhador'])
            ->orderByRaw("CASE status 
                WHEN 'preparacao' THEN 1
                WHEN 'concluido' THEN 2
                WHEN 'entregue' THEN 3
                ELSE 4
            END")
            ->orderByRaw('delivery_date IS NULL')
            ->orderBy('delivery_date', 'asc')
            ->orderBy('id', 'asc');

        // Filtros aplicados pelo utilizador
        if ($request->filled('status')) {
            $query->status($request->status);
        }

        if ($request->filled('payment_status')) {
            $query->paymentStatus($request->payment_status);
        }

        if ($request->filled('client_name')) {
            $clientName = $request->client_name;
            $query->whereHas('client', function($q) use ($clientName) {
                $q->where('name', 'like', "%{$clientName}%");
            });
        }

        // Filtro de pesquisa
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('invoice', 'like', "%{$search}%")
                  ->orWhereHas('client', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        // Filtro de range de datas (baseado na data de encomenda)
        if ($request->filled('date_from')) {
            $query->whereDate('order_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('order_date', '<=', $request->date_to);
        }

        $orders = $query->paginate(15);
        return view('orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new order
     */
    public function create()
    {
        $clients = Client::orderBy('name')->get();
        $products = Product::active()
            ->with('subcategory.category')
            ->orderBy('id', 'asc')
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
            'invoice' => 'nullable|string|max:255',
            'manual_subtotal' => 'nullable|numeric|min:0',
            'manual_iva' => 'nullable|numeric|min:0',
            'order_date' => 'required|date',
            'ready_date' => 'nullable|date|after_or_equal:order_date',
            'delivery_date' => 'nullable|date|after_or_equal:ready_date',
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

            $hasInvoice = !empty($validated['invoice']);
            if ($hasInvoice && $request->filled('manual_subtotal')) {
                $subtotal = (float) $validated['manual_subtotal'];
            }
            $iva = $hasInvoice ? ($subtotal * 0.22) : 0;
            $total = $subtotal + $iva;


            // Compatibilidade com BD (delivery_date nao pode ficar null)
            $readyDate = $validated['ready_date'] ?? $validated['order_date'];
            $deliveryDate = $validated['status'] === 'entregue'
                ? Carbon::today()->toDateString()
                : $validated['order_date'];
            $paymentDate = ($validated['payment_status'] ?? null) === 'pago'
                ? Carbon::today()->toDateString()
                : null;

            // Criar encomenda
            $order = Order::create([
                'client_id' => $validated['client_id'],
                'vendedor_id' => Auth::id(),
                'trabalhador_id' => $validated['trabalhador_id'] ?? null,
                'invoice' => $validated['invoice'] ?? null,  // â† ADICIONADO
                'order_date' => $validated['order_date'],
                'ready_date' => $readyDate,
                'delivery_date' => $deliveryDate,
                'payment_date' => $paymentDate,
                'desired_date' => $validated['desired_date'],
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $total,
                'status' => $validated['status'],
                'payment_status' => $validated['payment_status'],
                'payment_method' => $validated['payment_method'] ?? null,
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
            ->orderBy('id', 'asc')
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
            'invoice' => 'nullable|string|max:255',
            'manual_subtotal' => 'nullable|numeric|min:0',
            'manual_iva' => 'nullable|numeric|min:0',
            'order_date' => 'required|date',
            'ready_date' => 'nullable|date|after_or_equal:order_date',
            'delivery_date' => 'nullable|date|after_or_equal:ready_date',
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

            $hasInvoice = !empty($validated['invoice']);
            if ($hasInvoice && $request->filled('manual_subtotal')) {
                $subtotal = (float) $validated['manual_subtotal'];
            }
            $iva = $hasInvoice ? ($subtotal * 0.22) : 0;
            $total = $subtotal + $iva;

            // Compatibilidade com BD (delivery_date nao pode ficar null)
            $readyDate = $validated['ready_date']
                ?? ($order->ready_date ? $order->ready_date->toDateString() : $validated['order_date']);
            $deliveryDate = $validated['status'] === 'entregue'
                ? ($order->delivery_date ? $order->delivery_date->toDateString() : Carbon::today()->toDateString())
                : $validated['order_date'];
            $paymentDate = $validated['payment_status'] === 'pago'
                ? ($order->payment_date ? $order->payment_date->toDateString() : Carbon::today()->toDateString())
                : null;

            // Atualizar encomenda
            $order->update([
                'client_id' => $validated['client_id'],
                'trabalhador_id' => $validated['trabalhador_id'] ?? null,
                'invoice' => $validated['invoice'] ?? null,
                'order_date' => $validated['order_date'],
                'ready_date' => $readyDate,
                'delivery_date' => $deliveryDate,
                'payment_date' => $paymentDate,
                'desired_date' => $validated['desired_date'],
                'subtotal' => $subtotal,
                'iva' => $iva,
                'total' => $total,
                'status' => $validated['status'],
                'payment_status' => $validated['payment_status'],
                'payment_method' => $validated['payment_method'] ?? null,
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

    /**
     * Quick status/payment updates from the order detail page.
     */
    public function quickUpdate(Request $request, Order $order)
    {
        $validated = $request->validate([
            'status' => 'nullable|in:preparacao,concluido,entregue',
            'payment_status' => 'nullable|in:nao_pago,pago,parcial',
        ]);

        if (!$request->filled('status') && !$request->filled('payment_status')) {
            return redirect()
                ->route('orders.show', $order)
                ->with('error', 'Nenhuma alteração foi enviada.');
        }

        $updates = [
            'trabalhador_id' => Auth::id(),
        ];

        if ($request->filled('status')) {
            $updates['status'] = $validated['status'];

            if ($validated['status'] === 'preparacao') {
                $updates['ready_date'] = $order->ready_date
                    ? $order->ready_date->toDateString()
                    : ($order->order_date ? $order->order_date->toDateString() : Carbon::today()->toDateString());
                $updates['delivery_date'] = $order->order_date
                    ? $order->order_date->toDateString()
                    : Carbon::today()->toDateString();
            } elseif ($validated['status'] === 'concluido') {
                $updates['ready_date'] = Carbon::today()->toDateString();
                $updates['delivery_date'] = $order->order_date
                    ? $order->order_date->toDateString()
                    : Carbon::today()->toDateString();
            } elseif ($validated['status'] === 'entregue') {
                $updates['ready_date'] = $order->ready_date ? $order->ready_date->toDateString() : Carbon::today()->toDateString();
                $updates['delivery_date'] = Carbon::today()->toDateString();
            }
        }

        if ($request->filled('payment_status')) {
            $updates['payment_status'] = $validated['payment_status'];
            $updates['payment_date'] = $validated['payment_status'] === 'pago'
                ? Carbon::today()->toDateString()
                : null;
        }

        $order->update($updates);

        return redirect()
            ->route('orders.show', $order)
            ->with('success', 'Estado atualizado com sucesso!');
    }
}






