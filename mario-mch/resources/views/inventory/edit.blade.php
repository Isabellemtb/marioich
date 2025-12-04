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

                        <div class="row">
                            <div class="col-md-6 mb-3">
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

                            <div class="col-md-6 mb-3">
                                <label for="to_remove" class="form-label">Quantité à supprimer (disponibles uniquement)</label>
                                <input type="number"
                                       class="form-control @error('to_remove') is-invalid @enderror"
                                       id="to_remove"
                                       name="to_remove"
                                       min="0"
                                       max="{{ $availableCount }}"
                                       value="0">
                                @error('to_remove')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">
                                    Maximum : {{ $availableCount }}
                                </small>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('inventory.show', $storeId) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Modifier
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
