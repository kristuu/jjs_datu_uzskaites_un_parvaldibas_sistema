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

    protected function responseInstances($instances = null, array $globalFilterFields = [])
    {
        if (!$instances->isEmpty()) {
            $total = $instances->count();
            return response()->json([
                'instances' => $instances,
                'total' => $total,
                'globalFilterFields' => $globalFilterFields
            ]);
        } else {
            return $this->sendResponse([
                'message' => __('validation.instance.none_found', [
                    'model' => __('validation.models.' . class_basename($className))
                ])
            ], 404);
        }
    }

    /**
     * Retrieve the column names and translated headers for a given model.
     */
    protected function getColumnNames($model) {
        $tableName = $model->getTable();

        $allColumns = DB::getSchemaBuilder()->getColumnListing($tableName);
        $hiddenColumns = $model->getHidden();
        $visibleColumns = array_diff($allColumns, $hiddenColumns);

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
    protected function getAll(string $className, array $globalFilterFields = [], array $relationships = [], array $fieldsToHide = [])
    {
        $this->checkClassExistence($className);

        // create an instance to access the getKeyName() method
        $model = new $className;

        $query = $className::query();

        if (!empty($relationships)) {
            foreach($relationships as $relation => $fields) {
                if(is_numeric($relation)) {
                    $query->with($fields);
                } elseif(is_array($fields)) {
                    $query->with([
                        $relation => function($query) use ($fields, $model, $relation){
                            $relatedModel = $model->$relation()->getRelated();
                            $primaryKey = $relatedModel->getKeyName();
                            $query->select(array_merge([$primaryKey], $fields));
                        }
                    ]);
                }
            }
        }

        if (!empty($columnOrder)) {
            $query->select($columnOrder);
        }

        $instances = $query->get();

        $instances = $instances->map(function ($instance) use ($fieldsToHide) {
            foreach($fieldsToHide as $relation => $fields) {
                if ($instance->$relation) {
                    if (is_array($fields)) {
                        foreach ($fields as $nestedrelation => $nestedfields) {
                            if ($instance->$relation->$nestedrelation) {
                                foreach ($nestedfields as $nestedfield) {
                                    unset($instance->$relation->$nestedrelation->$nestedfield);
                                }
                            }
                        }
                    } else {
                        $instance->$relation->makeHidden($fields);
                    }
                }
            }
            return $instance;
        });

        return $instances;
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

    protected function findById(string $className, $instanceId, array $relationships = [], array $columnOrder = [])
    {
        $this->checkClassExistence($className);

        if (!empty($columnOrder)) {
            $instance = $className::select($columnOrder)->with($relationships)->find($instanceId);
        } else {
            $instance = $className::with($relationships)->find($instanceId);
        }

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
