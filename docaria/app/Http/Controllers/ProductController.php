<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Subcategory;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $products = Product::with('subcategory.category')
        ->orderBy('id', 'asc')
        ->paginate(10);

        return view('products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        //Buscar as subcategorias todas
        $subcategories = Subcategory::orderBy('name')->get();
        return view('products.create', compact('subcategories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'label' => 'nullable|string|max:100',
            'subcategory_id' => 'required|exists:subcategories,id',
            'price' => 'required|numeric|min:00',
            'active' => 'required|boolean',
        ]);

        Product::create($validated);
        
        return redirect()
        ->route('products.index')
        ->with('success', 'Produto criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //

        $product->load('subcategory');
        return view('products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //

        $subcategories = Subcategory::orderBy('name')->get();
        return view('products.edit', compact('product', 'subcategories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //

       $validated = $request->validate([
            'name' => 'required|string|max:255',
            'label' => 'nullable|string|max:100',
            'subcategory_id' => 'required|exists:subcategories,id',
            'price' => 'required|numeric|min:0',
            'active' => 'required|boolean',
        ]);

        $product->update($validated);

        return redirect()
        ->route('products.index')
        ->with('success', 'Produto atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //

        $product->delete();

        return redirect()
        ->route('products.index')
        ->with('success', 'Produto removido com sucesso!');
    }
}
