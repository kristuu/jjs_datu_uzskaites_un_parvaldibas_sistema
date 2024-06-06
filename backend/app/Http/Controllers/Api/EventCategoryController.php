<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\EventCategoryRequest;
use App\Http\Traits\PaginationTrait;
use App\Models\EventCategory;
use Illuminate\Http\Request;

class EventCategoryController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = ['name'];

    public function getAllEventCategories()
    {
        return $this->getAll(EventCategory::class, $this->globalFilterFields);
    }

    public function getPaginatedEventCategories(Request $request)
    {
        return $this->getPaginated($request, EventCategory::class, [], $request->perPage, $this->globalFilterFields);
    }

    public function storeEventCategory(EventCategoryRequest $request)
    {
        return $this->store($request, EventCategory::class);
    }

    public function findEventCategoryById(int $id)
    {
        return $this->findById(EventCategory::class, $id);
    }

    public function updateEventCategory(EventCategoryRequest $request, string $id)
    {
        return $this->update($request, $id, EventCategory::class);
    }

    public function destroyEventCategory(string $id)
    {
        $this->destroy($id, EventCategory::class);
    }
}
