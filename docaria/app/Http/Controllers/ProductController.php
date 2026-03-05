<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Product::with('subcategory.category')
            ->orderBy('id', 'asc');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->paginate(10);

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
        $validated = $request->validate(
            $this->productRules(),
            $this->productMessages()
        );

        if ($request->hasFile('image')) {
            $validated['imageUrl'] = $this->storeProductImage($request->file('image'));
        }

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
        $validated = $request->validate(
            $this->productRules(),
            $this->productMessages()
        );

        if ($request->hasFile('image')) {
            $this->deleteLocalProductImage($product->imageUrl);
            $validated['imageUrl'] = $this->storeProductImage($request->file('image'));
        }

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
        $this->deleteLocalProductImage($product->imageUrl);

        $product->delete();

        return redirect()
        ->route('products.index')
        ->with('success', 'Produto removido com sucesso!');
    }

    /**
     * Guarda imagem do produto no disco public e devolve caminho relativo.
     */
    private function storeProductImage($image): string
    {
        return $image->store('products', 'public');
    }

    /**
     * Remove imagem local antiga (se for da pasta /storage/products).
     */
    private function deleteLocalProductImage(?string $imageUrl): void
    {
        if (empty($imageUrl)) {
            return;
        }

        $relativePath = $imageUrl;

        if (str_starts_with($relativePath, 'http://') || str_starts_with($relativePath, 'https://')) {
            $path = parse_url($relativePath, PHP_URL_PATH) ?? '';
            if (str_starts_with($path, '/storage/')) {
                $relativePath = str_replace('/storage/', '', $path);
            }
        } elseif (str_starts_with($relativePath, '/storage/')) {
            $relativePath = str_replace('/storage/', '', $relativePath);
        } elseif (str_starts_with($relativePath, 'storage/')) {
            $relativePath = str_replace('storage/', '', $relativePath);
        }

        if (Storage::disk('public')->exists($relativePath)) {
            Storage::disk('public')->delete($relativePath);
        }
    }

    private function productRules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'label' => 'nullable|string|max:100',
            'subcategory_id' => 'required|exists:subcategories,id',
            'price' => 'required|numeric|min:0',
            'active' => 'required|boolean',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:3072',
        ];
    }

    private function productMessages(): array
    {
        return [
            'image.uploaded' => 'Falha no envio da imagem. Verifique o tamanho do ficheiro e tente novamente.',
            'image.image' => 'O ficheiro selecionado tem de ser uma imagem válida.',
            'image.mimes' => 'A imagem deve estar num formato válido: JPG, JPEG, PNG ou WEBP.',
            'image.max' => 'A imagem não pode exceder 3,00MB.',
        ];
    }
}
