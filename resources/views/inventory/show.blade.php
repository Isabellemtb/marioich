@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Liste DVD - Store {{ $storeId }}</h5>
                    <div>
                        <a href="{{ route('inventory.create', $storeId) }}" class="btn btn-primary btn-sm me-2">
                            <i class="bi bi-plus-circle"></i> Ajouter
                        </a>
                        <a href="{{ route('inventory.index') }}" class="btn btn-secondary btn-sm">
                            <i class="bi bi-arrow-left"></i> Retour
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if (empty($dvds))
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            Aucun DVD disponible dans ce magasin.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nom DVD</th>
                                        <th>Nombre d'exemplaires</th>
                                        <th>Lieu du store</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        // Groupe par film pour compter les exemplaires
                                        $groupedDvds = [];
                                        foreach ($dvds as $dvd) {
                                            $filmId = $dvd['film_id'];
                                            if (!isset($groupedDvds[$filmId])) {
                                                $groupedDvds[$filmId] = [
                                                    'film_title' => $dvd['film_title'],
                                                    'count' => 0
                                                ];
                                            }
                                            $groupedDvds[$filmId]['count']++;
                                        }
                                    @endphp

                                    @foreach ($groupedDvds as $filmId => $dvdInfo)
                                        <tr>
                                            <td><strong>{{ $dvdInfo['film_title'] }}</strong></td>
                                            <td>{{ $dvdInfo['count'] }}</td>
                                            <td>Store {{ $storeId }}</td>
                                            <td>
                                                <a href="{{ route('inventory.detail', [$storeId, $filmId]) }}" class="btn btn-primary rounded-circle" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <p class="text-muted">
                                <i class="bi bi-info-circle"></i>
                                Total : <strong>{{ count($groupedDvds) }}</strong> film(s) différent(s) | <strong>{{ count($dvds) }}</strong> DVD(s) au total
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
