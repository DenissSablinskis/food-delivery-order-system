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
                <p class="profile-eyebrow">{{ __('messages.profileSubheader') }}</p>
                <h1>{{ __('messages.profileHeader') }}</h1>
            </div>
        </div>

        <dl class="profile-details">
            <div class="profile-detail">
                <dt>{{ __('messages.profileUsername') }}</dt>
                <dd>{{ $user->username }}</dd>
            </div>
            <div class="profile-detail">
                <dt>{{ __('messages.profileEmail') }}</dt>
                <dd>{{ $user->email }}</dd>
            </div>
            <div class="profile-detail">
                <dt>{{ __('messages.profileAddress') }}</dt>
                <dd>{{ $user->address }}</dd>
            </div>
        </dl>

        <a href="{{ route('profile.edit') }}" class="profile-edit-link">
            {{ __('messages.profileUpdate') }}
        </a>
    </section>
@endsection
