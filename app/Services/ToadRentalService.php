<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ToadRentalService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.toad.url', 'http://localhost:8180'), '/');
    }

    public function getAllRentals(): ?array
    {
        $url = $this->baseUrl . '/rentals';

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
            Log::error('Erreur API Rentals', ['msg' => $e->getMessage()]);
            return null;
        }
    }

    public function returnRental(string $id): bool
    {
        $url = $this->baseUrl . '/rentals/' . $id;

        try {
            $headers = ['Accept' => 'application/json', 'Content-Type' => 'application/json'];
            $token = $this->getUserToken();
            if ($token) {
                $headers['Authorization'] = "Bearer {$token}";
            }

            // 1. Récupérer la location existante
            $rental = Http::withHeaders($headers)->timeout(10)->get($url)->json();
            if (empty($rental)) {
                return false;
            }

            // 2. Remettre les champs scalaires avec statusId=1 (terminée) + returnDate
            $payload = [
                'rentalId'    => $rental['rentalId'],
                'rentalDate'  => $rental['rentalDate'],
                'returnDate'  => now()->format('Y-m-d\TH:i:s'),
                'inventoryId' => $rental['inventoryId'],
                'customerId'  => $rental['customerId'],
                'staffId'     => $rental['staffId'],
                'statusId'    => 1,
                'lastUpdate'  => now()->format('Y-m-d\TH:i:s'),
            ];

            $response = Http::withHeaders($headers)->timeout(10)->put($url, $payload);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Erreur API Retour location', ['msg' => $e->getMessage()]);
            return false;
        }
    }

    private function getUserToken(): ?string
    {
        $userData = session('toad_user');
        return $userData['token'] ?? null;
    }
}
