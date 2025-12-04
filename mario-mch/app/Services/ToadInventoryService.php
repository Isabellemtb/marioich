<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ToadInventoryService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.toad.url', 'http://localhost:8180'), '/');
    }

    public function getAllInventories(): ?array
    {
        $url = $this->baseUrl . '/inventories';

        try {
            $headers = ['Accept' => 'application/json'];

            // Récupère le token JWT depuis la session
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
            Log::error('Erreur API Inventories', ['msg' => $e->getMessage()]);
            return null;
        }
    }

    public function getInventoriesByStore(int $storeId): ?array
    {
        $url = $this->baseUrl . '/inventories';

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
                $allInventories = $response->json();

                // Filtre les inventaires par magasin
                return array_filter($allInventories, function($inventory) use ($storeId) {
                    return ($inventory['storeId'] ?? null) == $storeId;
                });
            }

            return null;
        } catch (\Throwable $e) {
            Log::error('Erreur API Inventories by Store', ['msg' => $e->getMessage()]);
            return null;
        }
    }

    /**
     * Crée un nouvel inventaire
     */
    public function createInventory(array $data): bool
    {
        $url = $this->baseUrl . '/inventories';

        try {
            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ];

            $token = $this->getUserToken();
            if ($token) {
                $headers['Authorization'] = "Bearer {$token}";
            }

            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->post($url, $data);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Erreur création inventaire', ['msg' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Supprime un inventaire par son ID
     */
    public function deleteInventory(int $inventoryId): bool
    {
        $url = $this->baseUrl . '/inventories/' . $inventoryId;

        try {
            $headers = ['Accept' => 'application/json'];

            $token = $this->getUserToken();
            if ($token) {
                $headers['Authorization'] = "Bearer {$token}";
            }

            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->delete($url);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('Erreur suppression inventaire', ['msg' => $e->getMessage()]);
            return false;
        }
    }

    /**
     * Récupère le token JWT depuis la session utilisateur
     */
    private function getUserToken(): ?string
    {
        $userData = session('toad_user');
        return $userData['token'] ?? null;
    }
}
