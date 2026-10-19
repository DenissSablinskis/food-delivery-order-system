@extends('layouts.app')

@section('main-class', 'admin-products-main')

@section('content')
    <section class="admin-products-page">
        <header class="admin-products-heading">
            <p class="admin-products-eyebrow">Administrēšana</p>
            <h1>Produktu pārvaldība</h1>
            <p>Pārskatiet katalogu un atjauniniet produktu informāciju.</p>
            <a class="admin-product-primary-action" href="{{ route('admin.products.create') }}">
                <span class="bi bi-plus-lg" aria-hidden="true"></span>
                Pievienot produktu
            </a>
        </header>

        @if(session('error'))
            <div class="admin-products-message admin-products-message-error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if(session('success'))
            <div class="admin-products-message admin-products-message-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-products-table-wrapper">
            <table class="admin-products-table" aria-label="Produktu saraksts">
                <thead>
                    <tr>
                        <th scope="col">Produkts</th>
                        <th scope="col">Daudzums noliktavā</th>
                        <th scope="col">Cena</th>
                        <th scope="col">Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="admin-products-product-cell">
                                <img src="{{ asset($product->image) }}" alt="" width="56" height="56">
                                <span>{{ $product->name }}</span>
                            </td>
                            <td data-label="Daudzums noliktavā">{{ $product->stock_quantity }}</td>
                            <td data-label="Cena">{{ number_format($product->unit_price, 2, ',', ' ') }} €</td>
                            <td class="admin-products-action-cell" data-label="Darbības">
                                <div class="admin-products-actions">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="admin-products-edit-action">
                                        <span class="bi bi-pencil" aria-hidden="true"></span>
                                        Rediģēt
                                    </a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Vai tiešām vēlaties dzēst šo produktu?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="admin-products-delete-action">
                                            <span class="bi bi-trash3" aria-hidden="true"></span>
                                            Dzēst
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="admin-products-empty" colspan="4">Produktu katalogā vēl nav preču.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
@endsection