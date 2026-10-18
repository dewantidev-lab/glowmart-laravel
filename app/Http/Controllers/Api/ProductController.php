<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Menampilkan daftar produk dan mendukung pencarian serta filter.
     */
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

        $products = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $products
        ], 200);
    }

    /**
     * Menyimpan produk baru.
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();

        $data['slug'] = Str::slug($data['name']) . '-' . Str::uuid();

        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')
                ->store('products', 'public');
        }

        $product = Product::create($data);

        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product->load('category')
        ], 201);
    }

    /**
     * Menampilkan detail satu produk berdasarkan product_id.
     */
    public function show($id)
    {
        $product = Product::with('category')
            ->where('product_id', $id)
            ->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $product
        ], 200);
    }

    /**
     * Memperbarui produk.
     */
    public function update(UpdateProductRequest $request, $id)
    {
        $product = Product::where('product_id', $id)->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $data = $request->validated();

        if (isset($data['name'])) {
            $data['slug'] = Str::slug($data['name']) . '-' . Str::uuid();
        }

        if ($request->hasFile('img')) {
            if ($product->img) {
                Storage::disk('public')->delete($product->img);
            }

            $data['img'] = $request->file('img')
                ->store('products', 'public');
        }

        $product->update($data);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product->fresh()->load('category')
        ], 200);
    }

    /**
     * Menghapus produk.
     */
    public function destroy($id)
    {
        $product = Product::where('product_id', $id)->first();

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }

        if ($product->img) {
            Storage::disk('public')->delete($product->img);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully'
        ], 200);
    }
}