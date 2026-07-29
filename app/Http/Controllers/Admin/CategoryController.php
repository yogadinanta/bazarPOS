<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->get();

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        return view('admin.categories.create');
    }

    public function store(Request $request)
    {
        Category::create([
            'name' => $request->name,
            'icon' => $request->icon,
        ]);

        return redirect('/admin/categories');
    }

    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

 public function update(Request $request, Category $category)
{
    $request->validate([
        'name' => 'required|string|max:255',
    ]);

    $iconName = $category->icon;

    if ($request->hasFile('icon')) {

        $file = $request->file('icon');

        $iconName = time() . '_' . $file->getClientOriginalName();

        $file->move(
            public_path('uploads/categories'),
            $iconName
        );
    }

    $category->update([
        'name' => $request->input('name'),
        'icon' => $iconName,
    ]);

    return redirect('/admin/categories')
        ->with('success', 'Kategori berhasil diupdate');
}

    public function destroy(Category $category)
    {
        $category->delete();

        return back();
    }
}