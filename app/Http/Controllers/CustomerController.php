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
     * Display a listing of the resource.
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
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('customer.create');
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
        $customer = $this->customerService->getCustomer($id);
        return view('customer.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
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
            'password'   => $request->input('password'),
        ];
        $success = $this->customerService->updateCustomer($id, $data);
        $message = $success ? 'Client modifié avec succès.' : 'Erreur lors de la modification.';
        return redirect()->route('customer.index')->with('message', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $success = $this->customerService->deleteCustomer($id);
        $message = $success ? 'Client supprimé avec succès.' : 'Erreur lors de la suppression.';
        return redirect()->route('customer.index')->with('message', $message);
    }
}
