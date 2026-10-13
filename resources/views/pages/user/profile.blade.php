@extends('layouts.app')

@section('content')

@if (session('success'))
    <div class="success-message">
        {{ session('success') }}
    </div>
@endif

<p>Lietotājvārds: {{ $user->username }}</p>
<p>E-pasts: {{ $user->email }}</p>
<p>Adrese: {{ $user->address }}</p>

<a href="{{ route('profile.edit') }}" class="submit-btn">
    Rediģēt profilu
</a>

@endsection