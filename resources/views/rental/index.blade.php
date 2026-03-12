@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Gestion des locations</h5>
                </div>

                <div class="card-body">
                    @if(empty($rentals))
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            Aucune location trouvée.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Film</th>
                                        <th>Date de location</th>
                                        <th>Date de retour</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($rentals as $rental)
                                    <tr>
                                        <td>{{ $rental['rentalId'] }}</td>
                                        <td><strong>{{ $rental['inventory']['film']['title'] ?? '-' }}</strong></td>
                                        <td>{{ \Carbon\Carbon::parse($rental['rentalDate'])->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @if(!empty($rental['returnDate']))
                                                {{ \Carbon\Carbon::parse($rental['returnDate'])->format('d/m/Y H:i') }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if(($rental['statusId'] ?? 0) === 3)
                                                <span class="badge bg-warning text-dark">En location</span>
                                            @elseif(($rental['statusId'] ?? 0) === 1)
                                                <span class="badge bg-success">Terminée</span>
                                            @elseif(($rental['statusId'] ?? 0) === 2)
                                                <span class="badge bg-info text-dark">Dans panier</span>
                                            @else
                                                <span class="badge bg-secondary">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if(($rental['statusId'] ?? 0) === 3)
                                                <form action="{{ route('rental.return', $rental['rentalId']) }}" method="POST"
                                                      onsubmit="return confirm('Confirmer le retour de ce film ?')">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-success" title="Retour">
                                                        <i class="bi bi-arrow-return-left"></i> Retour
                                                    </button>
                                                </form>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-3">
                            <p class="text-muted">
                                <i class="bi bi-info-circle"></i>
                                Total : <strong>{{ count($rentals) }}</strong> location(s)
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
