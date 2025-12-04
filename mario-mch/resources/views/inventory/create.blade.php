@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Ajouter un DVD - Store {{ $storeId }}</h5>
                </div>

                <div class="card-body">
                    <form action="{{ route('inventory.store', $storeId) }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="film_id" class="form-label">Film</label>
                            <select class="form-select @error('film_id') is-invalid @enderror"
                                    id="film_id"
                                    name="film_id"
                                    required
                                    onchange="updateFilmTitle()">
                                <option value="">-- Sélectionnez un film --</option>
                                @foreach ($films as $film)
                                    <option value="{{ $film['filmId'] ?? $film['id'] }}"
                                            data-title="{{ $film['title'] ?? '' }}">
                                        {{ $film['title'] ?? 'Sans titre' }}
                                    </option>
                                @endforeach
                            </select>
                            @error('film_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="film_title" class="form-label">Titre du DVD</label>
                                <input type="text"
                                       class="form-control"
                                       id="film_title"
                                       readonly
                                       placeholder="Sélectionnez un film">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="disponibilite" class="form-label">Disponibilité</label>
                                <select class="form-select @error('disponibilite') is-invalid @enderror"
                                        id="disponibilite"
                                        name="disponibilite">
                                    <option value="disponible">Disponible</option>
                                    <option value="loue">Loué</option>
                                    <option value="maintenance">En maintenance</option>
                                </select>
                                @error('disponibilite')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="store_id" class="form-label">Lieu Store</label>
                                <input type="text"
                                       class="form-control"
                                       id="store_id"
                                       value="Store {{ $storeId }}"
                                       readonly>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="quantity" class="form-label">Nombre d'exemplaires</label>
                                <input type="number"
                                       class="form-control @error('quantity') is-invalid @enderror"
                                       id="quantity"
                                       name="quantity"
                                       min="1"
                                       value="1"
                                       required>
                                @error('quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('inventory.show', $storeId) }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Ajouter
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function updateFilmTitle() {
    const select = document.getElementById('film_id');
    const titleInput = document.getElementById('film_title');
    const selectedOption = select.options[select.selectedIndex];

    if (selectedOption.value) {
        titleInput.value = selectedOption.getAttribute('data-title');
    } else {
        titleInput.value = '';
    }
}
</script>
@endsection
