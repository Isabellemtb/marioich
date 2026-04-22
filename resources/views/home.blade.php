@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Tableau de bord</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>Bienvenue sur l'application Mario !</h4>
                    <p class="text-muted">Vous êtes connecté au système de gestion des locations de films.</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
