<?php

namespace App\Traits;

use App\Models\Company;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;

trait CommonMethods
{
    public function uniqueName(): string
    {
        $uniqueness = uniqid(microtime(), true);
        $replacement = str_replace(' ', '_', $uniqueness);

        return uniqid($replacement, true);
    }

    public function bearerToken(string $token): string
    {
        $token = str_replace('Bearer', '', $token);

        return str_replace(' ', '', $token);
    }

    public function isJSON(string $string): bool
    {
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE);
    }

    /**
     * @param  array<string, mixed>  $data
     */
    public function apiJsonResponse(int $success = 1, ?string $message = null, array $data = [], int $exception_error_code = 200): \Illuminate\Http\JsonResponse
    {
        $response = [
            'success' => $success,
            'message' => $message,
            'data' => $data,
        ];

        return response()->json($response, $exception_error_code, [], JSON_NUMERIC_CHECK);
    }

    protected function resolveCompany(?string $domain): Company
    {
        $fallbackCompanyId = config('app.main_company_id', 1);

        $query = Company::query()->select(['id', 'title']);

        if (empty($domain)) {
            $company = $query->where('id', $fallbackCompanyId)->first();
        } else {
            $company = $query
                ->where('domain_prefix', $domain)
                ->orWhere('domain', $domain)
                ->first();
        }

        if (! $company) {
            $e = new ModelNotFoundException('Company not found.');
            $e->setModel(Company::class);
            throw $e;
        }

        return $company;
    }

    /**
     * @return array<int, string>
     */
    protected function getCompaniesList(?int $company_id = null, ?int $parent_id = null): array
    {
        return Company::orderBy('title')->pluck('title', 'id')->toArray();
    }

    protected function getAuthenticatedUser(): ?\Illuminate\Contracts\Auth\Authenticatable
    {
        return Auth::user();
    }
}
