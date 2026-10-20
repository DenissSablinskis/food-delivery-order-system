@extends('layouts.app')

@section('main-class', 'admin-products-main')

@section('content')
    <section class="admin-products-page">
        <header class="admin-products-heading">
            <p class="admin-products-eyebrow">Administrēšana</p>
            <h1>Lietotāja rediģēšana</h1>
            <p>Atjauniniet lietotāja konta informāciju un lomu.</p>
        </header>

        <form class="admin-product-form" method="post" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PATCH')

            <div class="admin-product-form-fields">
                <div class="admin-product-field">
                    <label for="username">Lietotājvārds</label>
                    <input id="username" name="username" type="text" value="{{ old('username', $user->username) }}">
                    @error('username')
                        <p class="admin-product-field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-product-field">
                    <label for="name">Vārds</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}">
                    @error('name')
                        <p class="admin-product-field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-product-field">
                    <label for="surname">Uzvārds</label>
                    <input id="surname" name="surname" type="text" value="{{ old('surname', $user->surname) }}">
                    @error('surname')
                        <p class="admin-product-field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-product-field">
                    <label for="email">E-pasts</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}">
                    @error('email')
                        <p class="admin-product-field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-product-field">
                    <label for="role">Loma</label>
                    <select id="role" name="role">
                        <option value="customer" {{ old('role', $user->role) === 'customer' ? 'selected' : '' }}>Lietotājs</option>
                        <option value="operator" {{ old('role', $user->role) === 'operator' ? 'selected' : '' }}>Operators</option>
                        <option value="courier" {{ old('role', $user->role) === 'courier' ? 'selected' : '' }}>Kurjers</option>
                        <option value="admin" {{ old('role', $user->role) === 'admin' ? 'selected' : '' }}>Administrators</option>
                    </select>
                    @error('role')
                        <p class="admin-product-field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="admin-product-form-actions">
                <a class="admin-product-secondary-action" href="{{ route('admin.users.index') }}">Atpakaļ</a>
                <button class="admin-product-primary-action" type="submit">
                    <span class="bi bi-check-lg" aria-hidden="true"></span>
                    Saglabāt izmaiņas
                </button>
            </div>
        </form>
    </section>
@endsection
