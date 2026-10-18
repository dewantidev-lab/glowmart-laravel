<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->min_price);
        }

        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->max_price);
        }

        return response()->json([
            'success' => true,
            'data' => $query->latest()->paginate(10),
        ]);
    }

    
public function store(StoreProductRequest $request)
{
    $validated = $request->validated();

    $validated['slug'] = Str::slug($validated['name'])
        . '-' . Str::uuid();

    if ($request->hasFile('img')) {
        $validated['img'] = $request->file('img')
            ->store('products', 'public');
    }

    $product = Product::create($validated);

    return response()->json([
        'success' => true,
        'message' => 'Produk berhasil ditambahkan.',
        'data' => $product->load('category'),
    ], 201);
}

    
public function update(UpdateProductRequest $request, string $id)
{
    $product = Product::find($id);

    if (!$product) {
        return response()->json([
            'success' => false,
            'message' => 'Produk tidak ditemukan.',
        ], 404);
    }

    $validated = $request->validated();

    if (isset($validated['name'])) {
        $validated['slug'] = Str::slug($validated['name'])
            . '-' . Str::uuid();
    }

    if ($request->hasFile('img')) {
        $validated['img'] = $request->file('img')
            ->store('products', 'public');
    }

    $product->update($validated);

    return response()->json([
        'success' => true,
        'message' => 'Produk berhasil diperbarui.',
        'data' => $product->fresh()->load('category'),
    ]);
}

    public function destroy(string $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Produk tidak ditemukan.',
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil dihapus.',
        ]);
    }
}