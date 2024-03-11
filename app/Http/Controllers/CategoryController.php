<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use App\Models\LogTransaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): Response
    {
        $categories = Category::query()
        ->latest()
        ->take(20); 

        if ($request->search) {
            $categories->where('categories.name', 'like', '%' . $request->search . '%')
                ->orWhere('categories.id', 'like', '%' . $request->search . '%');
        }

        $categories = $categories->get();

        return Inertia ::render('Admin/Categories/CategoryIndex', [
            'categories' => CategoryResource::collection($categories),
            'search' => $request->search, // Pasa el valor de búsqueda a la vista
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        $this->authorize('create', Category::class);

        return Inertia::render('Admin/Categories/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateCategoryRequest $request): RedirectResponse
    {
        $this->authorize('create', Category::class);

        $category = Category::create($request->validated());

        //Creating log transactions
        $details = 'Nombre: ' . $category->name . ', Descripción: ' . $category->description;
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Crear', // HTTP method used for the request
            'resource' => 'Categoría', // Name of the resource being accessed
            'resource_id' => $category?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);

        return to_route('categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): Response
    {
        $this->authorize('update', $category);

        return Inertia::render('Admin/Categories/Edit', [
            'category' => new CategoryResource($category)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateCategoryRequest $request, Category $category): RedirectResponse
    {
        $this->authorize('update', $category);
        //Old Data
        $oldName = $category->name;
        $oldDescription = $category->description;
        //update
        $category->update($request->validated());

        //Creating log transactions
        $details = '[Nombre: ' . $oldName . '. a: ' . $category->name . '], [Descripción: ' . $oldDescription . '. a: ' . $category->description . ']';
        LogTransaction::create([
             'user_id' => auth()->id(), // Assuming you have user authentication
             'action' => 'Actualizar', // HTTP method used for the request
             'resource' => 'Categoría', // Name of the resource being accessed
             'resource_id' => $category?->id, // ID of the resource, if applicable
             'details' => $details, // Any additional details you want to log
         ]);
        return to_route('categories.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);

        //Creating log transactions
        $details = 'Nombre: ' . $category->name . ', Descripción: ' . $category->description;
        LogTransaction::create([
            'user_id' => auth()->id(), // Assuming you have user authentication
            'action' => 'Eliminar', // HTTP method used for the request
            'resource' => 'Categoría', // Name of the resource being accessed
            'resource_id' => $category?->id, // ID of the resource, if applicable
            'details' => $details, // Any additional details you want to log
        ]);

        $category->delete();
        return back();
    }
}
