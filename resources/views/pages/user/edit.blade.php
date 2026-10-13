@extends('layouts.app')

@section('main-class', 'profile-main')

@section('content')
    <section class="profile-card profile-edit-card">
        <div class="profile-card-heading">
            <span class="profile-avatar" aria-hidden="true">
                <i class="bi bi-person-fill"></i>
            </span>
            <div>
                <p class="profile-eyebrow">Mans konts</p>
                <h1>Rediģēt profilu</h1>
            </div>
        </div>

        <form method="post" action="{{ route('profile.update') }}" class="profile-edit-form">
            @csrf
            @method('PUT')

            <div class="profile-form-field">
                <label for="email">{{ __('messages.registerEmail') }}</label>
                <input type="email" id="email" name="email" value="{{ old('email', auth()->user()->email) }}" required>
                @error('email')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="profile-form-field">
                <label for="username">{{ __('messages.registerUsername') }}</label>
                <input type="text" id="username" name="username" value="{{ old('username', auth()->user()->username) }}" required>
                @error('username')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="profile-form-field">
                <label for="address">Adrese</label>
                <input type="text" id="address" name="address" value="{{ old('address', auth()->user()->address) }}" required>
                @error('address')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>

            <div class="profile-form-actions">
                <a href="{{ route('profile.show') }}" class="profile-cancel-link">Atpakaļ</a>
                <button class="profile-edit-link" type="submit">Saglabāt izmaiņas</button>
            </div>
        </form>
    </section>
@endsection
