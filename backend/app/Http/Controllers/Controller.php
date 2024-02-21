<?php

namespace App\Http\Controllers;

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

    protected function getColumnNames($model) {
        $tableName = $model->getTable();

        $allColumns = DB::getSchemaBuilder()->getColumnListing($tableName);
        $hiddenColumns = $model->getHidden();
        $visibleColumns = array_diff($allColumns, $hiddenColumns);

        $returnColumns = [];

        foreach ($visibleColumns as $column) {
            $returnColumns[] = [
                'database' => $column,
                'translated' => __("validation.attributes." . $column)
            ];
        }

        return $returnColumns;
    }

    protected function sendError($list = null, $status = 422)
    {
        return response()->json($list, $status);
    }

    protected function checkClassExistence(string $className)
    {
        if (!is_subclass_of($className, Model::class)) {
            throw new \InvalidArgumentException("$className is not a valid model class");
        }
    }

    protected function getAll(string $className)
    {
        $this->checkClassExistence($className);

        $instances = $className::all();
        if ($instances) {
            return response()->json($instances);
        } else {
            return $this->sendResponse(['message' => __('validation.instance.none_found', [
                'model' => __('validation.models.' . class_basename($className))
            ])], 404);
        }
    }

    protected function getPaginated(Request $request, string $className, array $relationships = [], ?int $pages = 10)
    {
        $this->checkClassExistence($className);

        $pages = $pages ?? $request->pages;

        $instances = $className::with($relationships)->paginate($pages);

        return response()->json([$instances]);
    }

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
                return $this->sendResponse(['message' => __('validation.instance.creation_failed', [
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
