@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Liste des Store</h5>
                </div>

                <div class="card-body">
                    @if ($stores->isEmpty())
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle"></i>
                            Aucun Store disponible.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Nom</th>
                                        <th>Adresse</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stores as $store)
                                        <tr>
                                            <td><strong>Store {{ $store['store_id'] }}</strong></td>
                                            <td>{{ $store['count'] }} DVD(s) en stock</td>
                                            <td>
                                                <a href="{{ route('inventory.show', $store['store_id']) }}" class="btn btn-primary rounded-circle" style="width: 40px; height: 40px;">
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
                                Total : <strong>{{ $stores->count() }}</strong> magasin(s)
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
