@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Détail du stock - Store {{ $storeId }}</h5>
                    <div>
                        <a href="{{ route('inventory.edit', [$storeId, $filmId]) }}" class="btn btn-warning btn-sm me-2">
                            <i class="bi bi-pencil"></i> Modifier
                        </a>
                        <a href="{{ route('inventory.show', $storeId) }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="mb-4">
                        <label class="form-label fw-bold">Nom du DVD Film</label>
                        <p class="form-control-plaintext">{{ $filmTitle }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Nom du Store</label>
                        <p class="form-control-plaintext">Store {{ $storeId }}</p>
                    </div>

                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold">Total d'exemplaires</label>
                            <div class="alert alert-info mb-0">
                                <h4 class="mb-0">{{ $totalCount }}</h4>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold">Exemplaires disponibles</label>
                            <div class="alert alert-success mb-0">
                                <h4 class="mb-0">{{ $availableCount }}</h4>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <label class="form-label fw-bold">Exemplaires indisponibles</label>
                            <div class="alert alert-warning mb-0">
                                <h4 class="mb-0">{{ $unavailableCount }}</h4>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <h6 class="fw-bold">Liste des exemplaires individuels</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="table-light">
                                    <tr>
                                        <th>ID Inventaire</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($dvdList as $dvd)
                                        <tr>
                                            <td>#{{ $dvd['inventory_id'] }}</td>
                                            <td>
                                                @if($dvd['is_available'])
                                                    <span class="badge bg-success">Disponible</span>
                                                @else
                                                    <span class="badge bg-warning">En location</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2" class="text-center text-muted">Aucun exemplaire</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="alert alert-info">
                        <i class="bi bi-info-circle"></i>
                        Pour modifier la quantité d'exemplaires, cliquez sur le bouton "Modifier" ci-dessus.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
