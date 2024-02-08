<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function sendResponse($data = null, int $status = 200, array $headers = [])
    {
        switch(true) {
            case $status >= 100 && $status <= 199:
                $message = 'informational';
                break;
            case $status >= 200 && $status <= 299:
                $message = 'successful';
                break;
            case $status >= 300 && $status <= 399:
                $message = 'redirection';
                break;
            case $status >= 400 && $status <= 499:
                $message = 'clientError';
                break;
            case $status >= 500 && $status <= 599:
                $message = 'serverError';
                break;
            default:
                $message = 'unknownError';
                break;
        };

        $response = [
          'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $status, $headers);
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
            return $this->sendResponse($instances);
        } else {
            return $this->sendResponse(['message' => 'No instances of ' . class_basename($className) . ' found'], 404);
        }
    }

    protected function getPaginated(Request $request, string $className, array $relationships = [], ?int $pages = 10)
    {
        $this->checkClassExistence($className);

        $pages = $pages ?? $request->pages;

        $instances = $className::with($relationships)->paginate($pages);

        return $this->sendResponse($instances);
    }

    protected function store(FormRequest $formRequest, string $className)
    {
        try {
            $this->checkClassExistence($className);

            $validated = $formRequest->validated();

            $instance = $className::create($validated);

            if ($instance) {
                return $this->sendResponse(['message' => class_basename($className) . ' created successfully']);
            } else {
                return $this->sendResponse(['message' => class_basename($className) . ' creation failed'], 500);
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
            return $this->sendResponse(['message' => 'No ' . class_basename($className) . ' with id ' . $instanceId . ' found'], 404);
        }
    }

    protected function update(FormRequest $formRequest, $instanceId, string $className)
    {
        $this->checkClassExistence($className);

        $instance = $className::find($instanceId);

        $validated = $formRequest->validated();

        if ($instance) {
            $instance->update($validated);

            return $this->sendResponse(['message' => class_basename($className) . ' with id ' . $instanceId . ' updated successfully']);
        } else {
            return $this->sendResponse(['message' => class_basename($className) . ' with id ' . $instanceId . ' was not found'], 404);
        }
    }

    protected function destroy($instanceId, string $className)
    {
        $this->checkClassExistence($className);

        $instance = $className::find($instanceId);
        if (!$instance) {
            return $this->sendResponse(['message' => class_basename($className) . ' with id ' . $instanceId . ' not found'], 404);
        }

        $instance->delete();
        return $this->sendResponse(['message' => class_basename($className) . ' with id ' . $instanceId . ' deleted successfully']);
    }
}
