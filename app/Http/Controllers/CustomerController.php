<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ToadCustomerService;
use App\Services\ToadRentalService;

class CustomerController extends Controller
{
    private ToadCustomerService $customerService;
    private ToadRentalService $rentalService;

    public function __construct(ToadCustomerService $customerService, ToadRentalService $rentalService)
    {
        $this->customerService = $customerService;
        $this->rentalService = $rentalService;
    }

    /**
     * Affiche la liste des clients
     */
    public function index()
    {
        $customers = $this->customerService->getAllCustomers() ?? [];

        $allRentals = $this->rentalService->getAllRentals() ?? [];
        $customersWithActiveRentals = array_flip(array_unique(
            array_column(
                array_filter($allRentals, fn($r) => ($r['statusId'] ?? 0) === 3),
                'customerId'
            )
        ));

        return view('customer.index', compact('customers', 'customersWithActiveRentals'));
    }

    /**
     * Affiche le formulaire de création d'un client
     */
    public function create()
    {
        return view('customer.create');
    }

    /**
     * Enregistre un nouveau client en base
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Affiche les détails d’un client
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Affiche le formulaire d’édition d’un client
     */
    public function edit(string $id)
    {
        $customer = $this->customerService->getCustomer($id);
        return view('customer.edit', compact('customer'));
    }

    /**
     * Met à jour un client existant
     */
    public function update(Request $request, string $id)
    {
        $data = [
            'customerId' => (int) $id,
            'firstName'  => $request->input('firstName'),
            'lastName'   => $request->input('lastName'),
            'email'      => $request->input('email'),
            'active'     => (bool) $request->input('active'),
            'storeId'    => (int) $request->input('storeId'),
            'addressId'  => (int) $request->input('addressId'),
            'createDate' => $request->input('createDate'),
        ];
        $success = $this->customerService->updateCustomer($id, $data);
        $message = $success ? 'Client modifié avec succès.' : 'Erreur lors de la modification.';
        return redirect()->route('customer.index')->with('message', $message);
    }

    /**
     * Supprime un client
     */
    public function destroy(string $id)
    {
        $success = $this->customerService->deleteCustomer($id);
        $message = $success ? 'Client supprimé avec succès.' : 'Erreur lors de la suppression.';
        return redirect()->route('customer.index')->with('message', $message);
    }
}
