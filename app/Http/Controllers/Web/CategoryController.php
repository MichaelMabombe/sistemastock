<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return view('web.categories.index', [
            'categories' => Category::query()->orderBy('name')->paginate(15),
        ]);
    }

    public function create()
    {
        return view('web.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        Category::create($data);

        return redirect()->route('web.categories.index')->with('success', 'Categoria criada.');
    }

    public function edit(Category $category)
    {
        return view('web.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:categories,name,' . $category->id],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $category->update($data);

        return redirect()->route('web.categories.index')->with('success', 'Categoria atualizada.');
    }

    public function destroy(Category $category)
    {
        $category->delete();

        return back()->with('success', 'Categoria removida.');
    }
}

