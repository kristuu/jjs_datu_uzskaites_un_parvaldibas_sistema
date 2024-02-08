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

    public function sendResponse($data = null, int $status = 200, array $headers = [])
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
            return $this->sendResponse(null, 404, ['message' => 'No instances of ' . class_basename($className) . ' found']);
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
                return $this->sendResponse(null, 200, ['message' => class_basename($className) . ' created successfully']);
            } else {
                return $this->sendResponse(null, 500, ['message' => class_basename($className) . ' creation failed']);
            }
        } catch (\Exception $exception) {
            Log::error($exception->getMessage());

            return $this->sendResponse(null, 500, [
                'message' => 'Error occurred while creating the ' . class_basename($className),
                'error' => $exception->getMessage(),
            ]);
        }
    }

    protected function findById(string $className, $instanceId)
    {
        $this->checkClassExistence($className);

        $instance = $className::find($instanceId);
        if ($instance) {
            return $this->sendResponse($instance);
        } else {
            return $this->sendResponse(null, 404, ['message' => 'No ' . class_basename($className) . ' with id ' . $instanceId . ' found']);
        }
    }


}
