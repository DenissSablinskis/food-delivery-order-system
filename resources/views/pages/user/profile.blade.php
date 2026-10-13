@extends('layouts.app')

@section('main-class', 'profile-main')

@section('content')
    <section class="profile-card">
        @if (session('success'))
            <div class="profile-success-message" role="status">
                {{ session('success') }}
            </div>
        @endif

        <div class="profile-card-heading">
            <span class="profile-avatar" aria-hidden="true">
                <i class="bi bi-person-fill"></i>
            </span>
            <div>
                <p class="profile-eyebrow">Mans konts</p>
                <h1>Profila informācija</h1>
            </div>
        </div>

        <dl class="profile-details">
            <div class="profile-detail">
                <dt>Lietotājvārds</dt>
                <dd>{{ $user->username }}</dd>
            </div>
            <div class="profile-detail">
                <dt>E-pasts</dt>
                <dd>{{ $user->email }}</dd>
            </div>
            <div class="profile-detail">
                <dt>Adrese</dt>
                <dd>{{ $user->address }}</dd>
            </div>
        </dl>

        <a href="{{ route('profile.edit') }}" class="profile-edit-link">
            Rediģēt profilu
        </a>
    </section>
@endsection
