@extends('layouts.app')

@section('content')
<div class="container">
    <h3>Modifier le client</h3>

    <form action="{{ route('customer.update', $customer['customerId']) }}" method="POST">
        @csrf
        @method('PUT')
        <input type="hidden" name="customerId" value="{{ $customer['customerId'] }}">
        <input type="hidden" name="active" value="{{ $customer['active'] ? '1' : '0' }}">
        <input type="hidden" name="storeId" value="{{ $customer['storeId'] }}">
        <input type="hidden" name="addressId" value="{{ $customer['addressId'] }}">
        <input type="hidden" name="createDate" value="{{ $customer['createDate'] }}">
        <input type="hidden" name="password" value="{{ $customer['password'] }}">

        <div class="mb-3">
            <label for="lastName" class="form-label">Nom</label>
            <input type="text" class="form-control @error('lastName') is-invalid @enderror"
                   id="lastName" name="lastName" value="{{ old('lastName', $customer['lastName']) }}" required>
            @error('lastName')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="firstName" class="form-label">Prénom</label>
            <input type="text" class="form-control @error('firstName') is-invalid @enderror"
                   id="firstName" name="firstName" value="{{ old('firstName', $customer['firstName']) }}" required>
            @error('firstName')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control @error('email') is-invalid @enderror"
                   id="email" name="email" value="{{ old('email', $customer['email']) }}" required>
            @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Enregistrer</button>
        <a href="{{ route('customer.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
@endsection
