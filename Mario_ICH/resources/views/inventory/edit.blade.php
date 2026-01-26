@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Modifier le stock - Store {{ $storeId }}</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('inventory.update', [$storeId, $filmId]) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="film_title" class="form-label">Nom du DVD Film</label>
                            <input type="text"
                                   class="form-control"
                                   id="film_title"
                                   value="{{ $filmTitle }}"
                                   readonly>
                        </div>

                        <div class="mb-3">
                            <label for="store_name" class="form-label">Nom du Store</label>
                            <input type="text"
                                   class="form-control"
                                   id="store_name"
                                   value="Store {{ $storeId }}"
                                   readonly>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="available_count" class="form-label">Nombre d'exemplaires disponibles</label>
                                <input type="number"
                                       class="form-control"
                                       id="available_count"
                                       value="{{ $availableCount }}"
                                       readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="unavailable_count" class="form-label">Nombre d'exemplaires indisponibles</label>
                                <input type="number"
                                       class="form-control"
                                       id="unavailable_count"
                                       value="{{ $unavailableCount }}"
                                       readonly>
                            </div>
                        </div>

                        <hr>

                        <div class="mb-3">
                            <label for="to_add" class="form-label">Quantité à ajouter</label>
                            <input type="number"
                                   class="form-control @error('to_add') is-invalid @enderror"
                                   id="to_add"
                                   name="to_add"
                                   min="0"
                                   value="0">
                            @error('to_add')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Champ caché pour to_remove (la suppression se fait individuellement) -->
                        <input type="hidden" name="to_remove" value="0">

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('inventory.show', $storeId) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Ajouter
                            </button>
                        </div>
                    </form>

                    <hr class="my-4">

                    <h6 class="fw-bold mb-3">Gestion des exemplaires individuels</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Inventaire</th>
                                    <th>Statut</th>
                                    <th width="120">Actions</th>
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
                                        <td>
                                            @if($dvd['is_available'])
                                                <form action="{{ route('inventory.delete-item', [$storeId, $filmId, $dvd['inventory_id']]) }}"
                                                      method="POST"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet exemplaire ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">
                                                        <i class="bi bi-trash"></i> Supprimer
                                                    </button>
                                                </form>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled>
                                                    <i class="bi bi-lock"></i> En location
                                                </button>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted">Aucun exemplaire</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="alert alert-info mt-3">
                        <i class="bi bi-info-circle"></i>
                        Vous ne pouvez supprimer que les exemplaires <strong>disponibles</strong>. Les DVDs en location sont protégés.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
