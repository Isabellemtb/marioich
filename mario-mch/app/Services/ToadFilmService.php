<?php


namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ToadFilmService
{
    private string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim((string) config('services.toad.url', 'http://localhost:8180'), '/');
    }

    public function getAllFilms(): ?array
    {
        $url = $this->baseUrl . '/films';

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
            Log::error('Erreur API Films', ['msg' => $e->getMessage()]);
            return null;
        }
    }

    public function getFilmById(int $id): ?array
    {
        $url = $this->baseUrl . '/films/' . $id;

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
            Log::error('Erreur API Film', ['msg' => $e->getMessage()]);
            return null;
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
    /**
 * Met à jour un film via l'API Toad
 */
    public function updateFilm($id, array $data): bool
    {
        $url = $this->baseUrl . '/films/' . $id;

        try {
            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ];
            $token = $this->getUserToken();
            if ($token) {
                $headers['Authorization'] = "Bearer {$token}";
            }

            Log::info('Tentative mise à jour film', ['url' => $url, 'data' => $data]);

            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->put($url, $data);

            if ($response->successful()) {
                Log::info('Mise à jour film OK', ['id' => $id]);
                return true;
            }

            Log::warning('Mise à jour film KO', ['status' => $response->status(), 'body' => $response->body()]);
            return false;

        } catch (\Throwable $e) {
            Log::error('Erreur mise à jour film', ['msg' => $e->getMessage()]);
            return false;
        }
    }
    public function createFilm(array $data): bool
    {
        $url = $this->baseUrl . '/films';

        try {
            $headers = [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ];
            $token = $this->getUserToken();
            if ($token) {
                $headers['Authorization'] = "Bearer {$token}";
            }

            Log::info('Tentative création film', ['url' => $url, 'data' => $data]);

            $response = Http::withHeaders($headers)
                ->timeout(10)
                ->post($url, $data);

            if ($response->successful()) {
                Log::info('Création film OK');
                return true;
            }

            Log::warning('Création film KO', ['status' => $response->status(), 'body' => $response->body()]);
            return false;

        } catch (\Throwable $e) {
            Log::error('Erreur création film', ['msg' => $e->getMessage()]);
            return false;
        }
    }
    public function deleteFilm($id): bool
    {
        $url = $this->baseUrl . '/films/' . $id;

        try {
            $headers = ['Accept' => 'application/json'];
            $token = $this->getUserToken();
            if ($token) {
                $headers['Authorization'] = "Bearer {$token}";
            }

            $response = Http::withHeaders($headers)
                ->withOptions(['verify' => false])
                ->delete($url);

            if ($response->successful()) {
                return true;
            }

            return false;
        } catch (\Throwable $e) {
            Log::error('Erreur suppression film', ['msg' => $e->getMessage()]);
            return false;
        }
    }

}