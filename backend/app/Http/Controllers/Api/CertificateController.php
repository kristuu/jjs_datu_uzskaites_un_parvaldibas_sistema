<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CertificateRequest;
use App\Http\Traits\PaginationTrait;
use App\Models\Certificate;

class CertificateController extends Controller
{
    use PaginationTrait;

    private array $globalFilterFields = ['name'];

    public function getAllCertificates()
    {
        return $this->getAll(
            Certificate::class,
            $this->globalFilterFields);
    }

    public function getPaginatedCertificates(CertificateRequest $request)
    {
        return $this->getPaginated($request,
            Certificate::class,
            [],
            $request->perPage,
            $this->globalFilterFields);
    }

    public function getUnusedCertificates($instructorId)
    {
        $certificates = Certificate::where(function ($query) use ($instructorId) {
            $query->doesntHave("instructor");
            if (!is_null($instructorId)) {
                $query->orWhereHas("instructor", function ($query) use ($instructorId) {
                    $query->where("id", "=", $instructorId);
                });
            }
        })->get();
        return $this->sendResponse($certificates);
    }

    public function storeCertificate(CertificateRequest $request)
    {
        return $this->store($request, Certificate::class);
    }

    public function findCertificateById(string $id)
    {
        return $this->findById(Certificate::class, $id);
    }

    public function updateCertificate(CertificateRequest $request, string $id)
    {
        return $this->update($request, $id, Certificate::class);
    }

    public function destroyCertificate(string $id)
    {
        return $this->destroy($id, Certificate::class);
    }

    private function getResponseWithMessage(string $message, int $status)
    {
        return response()->json(['status' => $status, 'message' => $message], $status);
    }
}
