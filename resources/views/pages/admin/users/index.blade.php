@extends('layouts.app')

@section('main-class', 'admin-products-main')

@section('content')
    <section class="admin-products-page">
        <header class="admin-products-heading">
            <p class="admin-products-eyebrow">Administrēšana</p>
            <h1>Lietotāju pārvaldība</h1>
            <p>Pārskatiet lietotāju sarakstu un pārvaldiet viņu kontus.</p>
        </header>

        @if (session('error'))
            <div class="admin-products-message admin-products-message-error" role="alert">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="admin-products-message admin-products-message-success" role="status">
                {{ session('success') }}
            </div>
        @endif

        <div class="admin-products-table-wrapper">
            <table class="admin-products-table" aria-label="Lietotāju saraksts">
                <thead>
                    <tr>
                        <th scope="col">Lietotājvārds</th>
                        <th scope="col">Vārds</th>
                        <th scope="col">Uzvārds</th>
                        <th scope="col">E-pasts</th>
                        <th scope="col">Loma</th>
                        <th scope="col">Darbības</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($users as $user)
                        <tr>
                            <td class="admin-products-product-cell" data-label="Lietotājvārds">{{ $user->username }}</td>
                            <td data-label="Vārds">{{ $user->name }}</td>
                            <td data-label="Uzvārds">{{ $user->surname }}</td>
                            <td data-label="E-pasts">{{ $user->email }}</td>
                            <td data-label="Loma">{{ $user->role }}</td>
                            <td class="admin-products-action-cell" data-label="Darbības">
                                <div class="admin-products-actions">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="admin-products-edit-action">
                                        <span class="bi bi-pencil" aria-hidden="true"></span>
                                        Rediģēt
                                    </a>

                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Vai tiešām vēlaties dzēst šo lietotāju?')">
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
                            <td class="admin-products-empty" colspan="6">Nav neviena lietotāja.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>

@endsection