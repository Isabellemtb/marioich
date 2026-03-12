<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ToadCustomerService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.toad.url', 'http://localhost:8180'), '/');
    }

    public function getAllCustomers(): ?array
    {
        $url = $this->baseUrl . '/customers';

        try {
            $headers = ['Accept' => 'application/json'];

            $token = $this->getUserToken();
            if ($token) {
                $headers['Authorization'] = "Bearer {$token}";
            }

            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->get($url);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Throwable $e) {
            Log::error('Erreur API Customers', ['msg' => $e->getMessage()]);
            return null;
        }
    }

    public function getCustomer(string $id): ?array
    {
        $url = $this->baseUrl . '/customers/' . $id;

        try {
            $headers = ['Accept' => 'application/json'];
            $token = $this->getUserToken();
            if ($token) {
                $headers['Authorization'] = "Bearer {$token}";
            }

            $response = Http::withHeaders($headers)->timeout(10)->get($url);

            if ($response->successful()) {
                return $response->json();
            }

            return null;
        } catch (\Throwable $e) {
            Log::error('Erreur API Customer', ['msg' => $e->getMessage()]);
            return null;
        }
    }

    public function updateCustomer(string $id, array $data): bool
    {
        $url = $this->baseUrl . '/customers/' . $id;

        try {
            $headers = ['Accept' => 'application/json', 'Content-Type' => 'application/json'];
            $token = $this->getUserToken();
            if ($token) {
                $headers['Authorization'] = "Bearer {$token}";
            }

            $response = Http::withHeaders($headers)->timeout(10)->put($url, $data);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Erreur API Update Customer', ['msg' => $e->getMessage()]);
            return false;
        }
    }

    public function deleteCustomer(string $id): bool
    {
        $url = $this->baseUrl . '/customers/' . $id;

        try {
            $headers = ['Accept' => 'application/json'];
            $token = $this->getUserToken();
            if ($token) {
                $headers['Authorization'] = "Bearer {$token}";
            }

            $response = Http::withHeaders($headers)->timeout(10)->delete($url);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Erreur API Delete Customer', ['msg' => $e->getMessage()]);
            return false;
        }
    }

    private function getUserToken(): ?string
    {
        $userData = session('toad_user');
        return $userData['token'] ?? null;
    }
}
