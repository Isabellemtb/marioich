@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Modifier le film</h3>

    <form action="{{ route('films.update', $film['filmId'] ?? $film['id']) }}" method="POST">
        @csrf
        @method('PUT')

        <!-- Titre -->
        <div class="mb-3">
            <label for="title" class="form-label">Titre</label>
            <input type="text" class="form-control" id="title" name="title" 
                   value="{{ old('title', $film['title']) }}" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description">{{ old('description', $film['description']) }}</textarea>
        </div>

        <!-- Langue -->
        <div class="mb-3">
            <label for="language_id" class="form-label">Langue</label>
            <select class="form-control" id="language_id" name="language_id">
                <option value="">-- Aucune --</option>
                @foreach ($languages as $id => $name)
                    <option value="{{ $id }}"
                        {{ old('language_id', $film['originalLanguageId'] ?? '') == $id ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>
        </div>


        <!-- Année -->
        <div class="mb-3">
            <label for="release_year" class="form-label">Année</label>
            <input type="number" class="form-control" id="release_year" name="release_year"
                   value="{{ old('release_year', $film['releaseYear']) }}" required>
        </div>

        <!-- Durée -->
        <div class="mb-3">
            <label for="length" class="form-label">Durée (min)</label>
            <input type="number" class="form-control" id="length" name="length"
                   value="{{ old('length', $film['length']) }}">
        </div>

        <!-- Note -->
        <div class="mb-3">
            <label for="rating" class="form-label">Note</label>
            <input type="text" class="form-control" id="rating" name="rating"
                   value="{{ old('rating', $film['rating']) }}">
        </div>

        <!-- Champs cachés obligatoires pour l'API -->
        <input type="hidden" name="rental_duration" value="{{ $film['rentalDuration'] ?? 3 }}">
        <input type="hidden" name="rental_rate" value="{{ $film['rentalRate'] ?? 4.99 }}">
        <input type="hidden" name="replacement_cost" value="{{ $film['replacementCost'] ?? 19.99 }}">

        <!-- Boutons -->
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('films.show', $film['filmId'] ?? $film['id']) }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
