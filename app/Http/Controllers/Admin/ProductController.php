<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->latest()->get();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $imageName = null;

        // UPLOAD IMAGE
        if ($request->hasFile('image')) {

            $image = $request->file('image');

            $imageName = time() . '_' . $image->getClientOriginalName();

            $image->move(
                public_path('uploads/products'),
                $imageName
            );
        }

        Product::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'image' => $imageName,
        ]);

        return redirect('/admin/products');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();

        return view('admin.products.edit', compact(
            'product',
            'categories'
        ));
    }

    public function update(Request $request, Product $product)
    {
        
        $imageName = $product->image;

        // CHECK IMAGE UPDATE
 if ($request->hasFile('image')) {
    $image = $request->file('image');

    $imageName = time() . '.' . $image->getClientOriginalExtension();

    $image->move(public_path('uploads/products'), $imageName);
}

        $product->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'price' => $request->price,
            'discount_price' => $request->discount_price,
            'image' => $imageName,
        ]);

        return redirect('/admin/products');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back();
    }
}