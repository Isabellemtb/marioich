@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Gestion des utilisateurs</h5>
                </div>

                <div class="card-body">
                    @if(empty($customers))
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            Aucun utilisateur disponible.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>ID</th>
                                        <th>Nom</th>
                                        <th>Prénom</th>
                                        <th>Email</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($customers as $customer)
                                    <tr>
                                        <td>{{ $customer['customerId'] }}</td>
                                        <td><strong>{{ $customer['lastName'] }}</strong></td>
                                        <td>{{ $customer['firstName'] }}</td>
                                        <td>{{ $customer['email'] }}</td>
                                        <td>
                                            @if($customer['active'])
                                                <span class="badge bg-success">Actif</span>
                                            @else
                                                <span class="badge bg-secondary">Inactif</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('customer.edit', $customer['customerId']) }}" class="btn btn-sm btn-warning" title="Modifier">
                                                    <i class="bi bi-pencil"></i>
                                                </a>
                                                @if(isset($customersWithActiveRentals[$customer['customerId']]))
                                                    <button type="button" class="btn btn-sm btn-danger disabled" title="Impossible de supprimer : ce client a des locations en cours" disabled>
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                @else
                                                    <form action="{{ route('customer.destroy', $customer['customerId']) }}" method="POST" style="display:inline"
                                                          onsubmit="return confirm('Supprimer ce client ?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <p class="text-muted">
                                <i class="bi bi-info-circle"></i>
                                Total : <strong>{{ count($customers) }}</strong> utilisateur(s)
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
