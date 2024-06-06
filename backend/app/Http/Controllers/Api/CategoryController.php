<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Http\Requests\CountryRequest;
use App\Http\Traits\PaginationTrait;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = ['name'];

    public function getAllCategories()
    {
        return $this->getAll(Category::class, $this->globalFilterFields);
    }

    public function getPaginatedCategories(Request $request)
    {
        return $this->getPaginated($request,
            Category::class,
            [],
            $request->perPage,
            $this->globalFilterFields);
    }

    public function storeCategory(CountryRequest $request)
    {
        return $this->store($request, Category::class);
    }

    public function findCategoryById(string $id)
    {
        return $this->findById(Category::class, $id);
    }

    public function updateCategory(CategoryRequest $request, string $id)
    {
        return $this->update($request, $id, Category::class);
    }

    public function destroyCategory(string $id)
    {
        return $this->destroy($id, Category::class);
    }

    private function getResponseWithMessage(string $message, int $status)
    {
        return response()->json(['status' => $status, 'message' => $message], $status);
    }
}
