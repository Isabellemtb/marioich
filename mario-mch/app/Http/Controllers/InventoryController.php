<?php

namespace App\Http\Controllers;

use App\Services\ToadInventoryService;
use App\Services\ToadFilmService;

class InventoryController extends Controller
{
    private ToadInventoryService $inventoryService;
    private ToadFilmService $filmService;

    public function __construct(ToadInventoryService $inventoryService, ToadFilmService $filmService)
    {
        $this->middleware('auth');
        $this->inventoryService = $inventoryService;
        $this->filmService = $filmService;
    }

    public function index()
    {
        // Récupère tous les inventaires depuis l'API
        $inventories = $this->inventoryService->getAllInventories();

        // Groupe les inventaires par magasin
        $storeGroups = [];
        if ($inventories) {
            foreach ($inventories as $inventory) {
                $storeId = $inventory['storeId'] ?? null;
                if ($storeId) {
                    if (!isset($storeGroups[$storeId])) {
                        $storeGroups[$storeId] = [
                            'store_id' => $storeId,
                            'count' => 0
                        ];
                    }
                    $storeGroups[$storeId]['count']++;
                }
            }
        }

        // Convertit en collection pour la vue
        $stores = collect(array_values($storeGroups));

        return view('inventory.index', [
            'stores' => $stores
        ]);
    }

    public function show($storeId)
    {
        // Récupère les inventaires pour ce magasin
        $inventories = $this->inventoryService->getInventoriesByStore($storeId);

        // Transforme pour la vue
        $dvds = [];
        if ($inventories) {
            foreach ($inventories as $inventory) {
                $dvds[] = [
                    'inventory_id' => $inventory['inventoryId'] ?? null,
                    'film_title' => $inventory['film']['title'] ?? 'N/A',
                    'film_id' => $inventory['filmId'] ?? null,
                ];
            }
        }

        return view('inventory.show', [
            'storeId' => $storeId,
            'dvds' => $dvds
        ]);
    }

    public function create($storeId)
    {
        // Récupère tous les films
        $allFilms = $this->filmService->getAllFilms();

        // Récupère les inventaires du store pour savoir quels films sont déjà présents
        $inventories = $this->inventoryService->getInventoriesByStore($storeId);

        // Extrait les IDs des films déjà présents dans ce store
        $filmIdsInStore = [];
        if ($inventories) {
            foreach ($inventories as $inventory) {
                $filmId = $inventory['filmId'] ?? null;
                if ($filmId && !in_array($filmId, $filmIdsInStore)) {
                    $filmIdsInStore[] = $filmId;
                }
            }
        }

        // Filtre les films pour ne garder que ceux qui ne sont pas dans le store
        $availableFilms = [];
        if ($allFilms) {
            foreach ($allFilms as $film) {
                $filmId = $film['filmId'] ?? $film['id'] ?? null;
                if ($filmId && !in_array($filmId, $filmIdsInStore)) {
                    $availableFilms[] = $film;
                }
            }
        }

        return view('inventory.create', [
            'storeId' => $storeId,
            'films' => $availableFilms
        ]);
    }

    public function detail($storeId, $filmId)
    {
        // Récupère les inventaires pour ce film dans ce store
        $inventories = $this->inventoryService->getInventoriesByStore($storeId);

        $filmTitle = null;
        $availableCount = 0;
        $unavailableCount = 0;

        if ($inventories) {
            foreach ($inventories as $inventory) {
                if (($inventory['filmId'] ?? null) == $filmId) {
                    $filmTitle = $inventory['film']['title'] ?? 'N/A';
                    // TODO: Vérifier le statut de disponibilité dans l'API
                    // Pour l'instant, on considère que tous sont disponibles
                    $availableCount++;
                }
            }
        }

        return view('inventory.detail', [
            'storeId' => $storeId,
            'filmId' => $filmId,
            'filmTitle' => $filmTitle,
            'availableCount' => $availableCount,
            'unavailableCount' => $unavailableCount,
            'totalCount' => $availableCount + $unavailableCount
        ]);
    }

    public function store($storeId)
    {
        $validated = request()->validate([
            'film_id' => 'required|integer',
            'quantity' => 'required|integer|min:1',
        ]);

        $filmId = (int)$validated['film_id'];
        $quantity = (int)$validated['quantity'];

        // Crée autant d'inventaires que demandé
        $success = true;
        for ($i = 0; $i < $quantity; $i++) {
            $data = [
                'filmId' => $filmId,
                'storeId' => (int)$storeId,
            ];

            if (!$this->inventoryService->createInventory($data)) {
                $success = false;
                break;
            }
        }

        if (!$success) {
            return redirect()->back()->with('error', 'Erreur lors de l\'ajout du DVD au stock.');
        }

        return redirect()->route('inventory.show', $storeId)
            ->with('success', 'DVD ajouté au stock avec succès !');
    }

    public function edit($storeId, $filmId)
    {
        // Récupère les inventaires pour ce film dans ce store
        $inventories = $this->inventoryService->getInventoriesByStore($storeId);

        $filmTitle = null;
        $availableCount = 0;
        $unavailableCount = 0;

        if ($inventories) {
            foreach ($inventories as $inventory) {
                if (($inventory['filmId'] ?? null) == $filmId) {
                    $filmTitle = $inventory['film']['title'] ?? 'N/A';

                    // TODO: Vérifier le statut de disponibilité dans l'API
                    // Pour l'instant, on considère que tous sont disponibles
                    $availableCount++;
                }
            }
        }

        return view('inventory.edit', [
            'storeId' => $storeId,
            'filmId' => $filmId,
            'filmTitle' => $filmTitle,
            'availableCount' => $availableCount,
            'unavailableCount' => $unavailableCount
        ]);
    }

    public function update($storeId, $filmId)
    {
        $validated = request()->validate([
            'to_add' => 'required|integer|min:0',
            'to_remove' => 'required|integer|min:0',
        ]);

        $toAdd = (int)$validated['to_add'];
        $toRemove = (int)$validated['to_remove'];

        // Récupère les inventaires actuels disponibles
        $inventories = $this->inventoryService->getInventoriesByStore($storeId);
        $availableInventories = [];

        if ($inventories) {
            foreach ($inventories as $inventory) {
                if (($inventory['filmId'] ?? null) == $filmId) {
                    // TODO: Vérifier le statut de disponibilité
                    // Pour l'instant, on considère tous comme disponibles
                    $availableInventories[] = $inventory;
                }
            }
        }

        // Vérifie qu'on ne supprime pas plus que disponible
        if ($toRemove > count($availableInventories)) {
            return redirect()->back()->with('error', 'Impossible de supprimer plus d\'exemplaires que disponibles.');
        }

        // Supprime les exemplaires demandés
        for ($i = 0; $i < $toRemove; $i++) {
            $inventoryId = $availableInventories[$i]['inventoryId'] ?? null;
            if ($inventoryId) {
                $this->inventoryService->deleteInventory($inventoryId);
            }
        }

        // Ajoute les nouveaux exemplaires
        for ($i = 0; $i < $toAdd; $i++) {
            $this->inventoryService->createInventory([
                'filmId' => (int)$filmId,
                'storeId' => (int)$storeId,
            ]);
        }

        return redirect()->route('inventory.show', $storeId)
            ->with('success', 'Stock mis à jour avec succès !');
    }
}
