@extends('layouts.app')

@section('main-class', 'admin-products-main')

@section('content')
    <section class="admin-products-page">
        <header class="admin-products-heading">
            <p class="admin-products-eyebrow">Administrēšana</p>
            <h1>Jauna produkta pievienošana</h1>
            <p>Aizpildiet produkta informāciju, lai to pievienotu katalogam.</p>
        </header>

        <form class="admin-product-form" method="post" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="admin-product-form-fields">
                <div class="admin-product-field admin-product-field-full">
                    <label for="image">Produkta attēls</label>
                    <input type="file" id="image" name="image" accept="image/*" required>
                    @error('image')
                        <p class="admin-product-field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-product-field admin-product-field-full">
                    <label for="name">Nosaukums</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                    @error('name')
                        <p class="admin-product-field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-product-field">
                    <label for="stock_quantity">Daudzums noliktavā</label>
                    <input id="stock_quantity" name="stock_quantity" type="number" min="0" value="{{ old('stock_quantity') }}" required>
                    @error('stock_quantity')
                        <p class="admin-product-field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-product-field">
                    <label for="unit">Mērvienība</label>
                    <input id="unit" name="unit" type="text" value="{{ old('unit') }}" required>
                    @error('unit')
                        <p class="admin-product-field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-product-field">
                    <label for="unit_price">Cena (€)</label>
                    <input id="unit_price" name="unit_price" type="number" step="0.01" min="0.01" value="{{ old('unit_price') }}" required>
                    @error('unit_price')
                        <p class="admin-product-field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="admin-product-form-actions">
                <a class="admin-product-secondary-action" href="{{ route('admin.products.index') }}">Atpakaļ</a>
                <button class="admin-product-primary-action" type="submit">
                    <span class="bi bi-plus-lg" aria-hidden="true"></span>
                    Pievienot produktu
                </button>
            </div>
        </form>
    </section>
@endsection