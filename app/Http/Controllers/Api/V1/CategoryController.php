<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $categories = Category::select(['id', 'name', 'slug'])->latest()->get();
            return response()->successResponse(CategoryResource::collection($categories), 'Category list');
        }catch(Exception $exception){
            Log::info($exception->getMessage());
            return response()->errorResponse();
        }

    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try{
            $category = Category::create($request->validated());
            return response()->successResponse(new CategoryResource($category), 'Category created successfully', 201);
        }catch(Exception $exception){
            Log::info($exception->getMessage());
            return response()->errorResponse();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $slug)
    {
        try{
            $category = Category::where(['id' => $slug])->first();
            if($category){
                return response()->successResponse(new CategoryResource($category), 'Category details', 200);
            }else{
                return response()->notFoundResponse();
            }

        }catch(Exception $exception){
            Log::info($exception->getMessage());
            return response()->errorResponse();
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, string $id)
    {
       // try{
            $category = Category::findOrFail($id);
            $category->update($request->validated());
            $updatedCategory = Category::findOrFail($id);
            return response()->successResponse(new CategoryResource($category), 'Category updated successfully', 201);
//        }catch(Exception $exception){
//            Log::info($exception->getMessage());
//            return response()->errorResponse();
//        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $article = Category::find($id);
            if($article){
                $article->delete();
                return response()->successResponse([], 'Category deleted successfully', 201);
            }else{
                return response()->notFoundResponse();
            }
        }catch(Exception $exception){
            Log::info($exception->getMessage());
            return response()->errorResponse();
        }
    }
}
