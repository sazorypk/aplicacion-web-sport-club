@extends('layouts.public')

@section('title', 'Mi perfil | Sport Club')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="mb-4">
                <h1 class="h2 fw-bold">Mi perfil</h1>
                <p class="text-secondary mb-0">Administra tu información y la seguridad de tu cuenta.</p>
            </div>
            <div class="d-grid gap-4">
                <div class="card shadow-sm"><div class="card-body p-4">@include('profile.partials.profile-photo-form')</div></div>
                <div class="card shadow-sm"><div class="card-body p-4">@include('profile.partials.update-profile-information-form')</div></div>
                <div class="card shadow-sm"><div class="card-body p-4">@include('profile.partials.update-password-form')</div></div>
                <div class="card border-danger-subtle shadow-sm"><div class="card-body p-4">@include('profile.partials.delete-user-form')</div></div>
            </div>
        </div>
    </div>
</div>
@endsection
