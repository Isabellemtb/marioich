<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ToadRentalService;

class RentalController extends Controller
{
    private ToadRentalService $rentalService;

    public function __construct(ToadRentalService $rentalService)
    {
        $this->rentalService = $rentalService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all = $this->rentalService->getAllRentals() ?? [];
        // On exclut les statusId=2 (dans panier)
        $rentals = array_values(array_filter($all, fn($r) => ($r['statusId'] ?? 0) !== 2));
        return view('rental.index', compact('rentals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('films.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function returnRental(string $id)
    {
        $success = $this->rentalService->returnRental($id);

        if ($success) {
            return redirect()->route('rental.index')->with('success', 'Retour enregistré avec succès.');
        }

        return redirect()->route('rental.index')->with('error', 'Erreur lors de l\'enregistrement du retour.');
    }
}
