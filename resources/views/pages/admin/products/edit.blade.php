@extends('layouts.app')

@section('main-class', 'admin-products-main')

@section('content')
    <section class="admin-products-page">
        <header class="admin-products-heading">
            <p class="admin-products-eyebrow">Administrēšana</p>
            <h1>Produkta rediģēšana</h1>
            <p>Atjauniniet produkta attēlu, nosaukumu, atlikumu un cenu.</p>
        </header>

        <form class="admin-product-form" method="post" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')

            <div class="admin-product-image-section">
                <div>
                    <h2>Produkta attēls</h2>
                    <p>Pašreizējais attēls un jauna attēla augšupielāde.</p>
                </div>
                <img class="admin-product-current-image" src="{{ asset($product->image) }}" alt="{{ $product->name }}">
            </div>

            <div class="admin-product-form-fields">
                <div class="admin-product-field admin-product-field-full">
                    <label for="image">Jauns attēls</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    @error('image')
                        <p class="admin-product-field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-product-field admin-product-field-full">
                    <label for="name">Nosaukums</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $product->name) }}">
                    @error('name')
                        <p class="admin-product-field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-product-field">
                    <label for="stock_quantity">Daudzums noliktavā</label>
                    <input id="stock_quantity" name="stock_quantity" type="number" min="0" value="{{ old('stock_quantity', $product->stock_quantity) }}">
                    @error('stock_quantity')
                        <p class="admin-product-field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>

                <div class="admin-product-field">
                    <label for="unit_price">Cena (€)</label>
                    <input id="unit_price" name="unit_price" type="number" step="0.01" min="0.01" value="{{ old('unit_price', $product->unit_price) }}">
                    @error('unit_price')
                        <p class="admin-product-field-error" role="alert">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="admin-product-form-actions">
                <a class="admin-product-secondary-action" href="{{ route('admin.products.index') }}">Atpakaļ</a>
                <button class="admin-product-primary-action" type="submit">
                    <span class="bi bi-check-lg" aria-hidden="true"></span>
                    Saglabāt izmaiņas
                </button>
            </div>
        </form>
    </section>
@endsection
