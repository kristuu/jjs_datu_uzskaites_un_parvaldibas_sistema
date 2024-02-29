<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    const STATUS_MESSAGE = [
        "no_records_found" => 'No records found',
        "no_such_user_found" => 'No such user found',
        "no_intrustors_found" => 'No instructors found'
        // add your more messages here
    ];

    protected function sendResponse($data = null, int $status = 200, array $headers = [])
    {
        if (!is_null($data)) {
            $response = $data;
        }

        return response()->json($response, $status, $headers);
    }

    /**
     * Retrieve the column names and translated headers for a given model.
     */
    protected function getColumnNames($model) {
        $tableName = $model->getTable();

        $allColumns = DB::getSchemaBuilder()->getColumnListing($tableName);
        $hiddenColumns = $model->getHidden();
        $visibleColumns = array_diff($allColumns, $hiddenColumns);

        $visibleColumns = array_diff($visibleColumns, ['created_at', 'updated_at']);

        $returnColumns = [];

        foreach ($visibleColumns as $column) {
            $returnColumns[] = [
                'field' => $column,
                'header' => $column === 'created_at' || $column === 'updated_at' || $column === 'id'
                    ? __("validation.attributes." . $column)
                    : __("validation.attributes." . $tableName . "." . $column)
            ];
        }

        return $returnColumns;
    }

    protected function sendError($list = null, $status = 422)
    {
        return response()->json($list, $status);
    }

    /**
     * Check if the given class name exists and is a subclass of the Model class.
     */
    protected function checkClassExistence(string $className)
    {
        if (!is_subclass_of($className, Model::class)) {
            throw new \InvalidArgumentException("$className is not a valid model class");
        }
    }

    /**
     * Retrieve all instances of the given class and append global filter fields (searchable on front-end).
     */
    protected function getAll(string $className, array $globalFilterFields = [])
    {
        $this->checkClassExistence($className);

        $instances = $className::all()->map(function ($instance) {
            unset($instance->created_at, $instance->updated_at);
            return $instance;
        });

        if ($instances) {
            $total = $instances->count();
            return response()->json(['instances' => $instances, 'total' => $total, 'globalFilterFields' => $globalFilterFields]);
        } else {
            return $this->sendResponse(['message' => __('validation.instance.none_found', [
                'model' => __('validation.models.' . class_basename($className))
            ])], 404);
        }
    }

    /**
     * Retrieve instances of a class in a paginated format.
     */
    protected function getPaginated(Request $request, string $className, array $relationships = [], ?int $pages = 10, array $globalFilterFields = [])
    {
        $this->checkClassExistence($className);

        $pages = $pages ?? $request->pages;

        $instances = $className::with($relationships)->paginate($pages);

        return response()->json([$instances, 'globalFilterFields' => $globalFilterFields]);
    }

    /**
     * Store an instance of a class using the provided FormRequest object and class name.
     */
    protected function store(FormRequest $formRequest, string $className)
    {
        try {
            $this->checkClassExistence($className);

            $validated = $formRequest->validated();

            $instance = $className::create($validated);

            if ($instance) {
                return $this->sendResponse(['message' => __('validation.instance.creation', [
                    'model' => __('validation.models.' . class_basename($className))
                ])]);
            } else {
                return $this->sendResponse(['message' => __('`validation.instance.creation_failed', [
                    'model' => __('validation.models.' . class_basename($className))
                ])], 500);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return $this->sendResponse([
                'message' => 'Error occurred while creating the ' . class_basename($className),
                'error' => $exception->getMessage(),
            ], 500);
        }
    }

    protected function findById(string $className, $instanceId)
    {
        $this->checkClassExistence($className);

        $instance = $className::find($instanceId);
        if ($instance) {
            return $this->sendResponse($instance);
        } else {
            return $this->sendResponse(['message' => __('validation.instance.not_found', [
                'model' => __('validation.models.' . class_basename($className)),
                'id' => $instanceId])], 404);
        }
    }

    /**
     * Update an instance of a class using the provided FormRequest object, instance ID, and class name.
     */
    protected function update(FormRequest $formRequest, $instanceId, string $className)
    {
        $this->checkClassExistence($className);

        $instance = $className::find($instanceId);

        $validated = $formRequest->validated();

        if ($instance) {
            $instance->update($validated);

            return $this->sendResponse(['message' => __('validation.instance.update', [
                'model' => __('validation.models.' . class_basename($className)),
                'id' => $instanceId])]);
        } else {
            return $this->sendResponse(['message' => __('validation.instance.not_found', [
                'model' => __('validation.models.' . class_basename($className)),
                'id' => $instanceId])], 404);
        }
    }

    /**
     * Delete an instance of a class using the provided instance ID and class name.
     */
    protected function destroy($instanceId, string $className)
    {
        $this->checkClassExistence($className);

        $instance = $className::find($instanceId);
        if (!$instance) {
            return $this->sendResponse(['message' => __('validation.instance.not_found', [
                'model' => __('validation.models.' . class_basename($className)),
                'id' => $instanceId])], 404);
        }

        $instance->delete();
        return $this->sendResponse(['message' => __('validation.instance.deleted', [
            'model' => __('validation.models.' . class_basename($className)),
            'id' => $instanceId])]);
    }
}
