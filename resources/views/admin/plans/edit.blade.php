@extends('layouts.public')
@section('title', 'Editar plan')
@section('content')<div class="container py-5"><h1 class="mb-4">Editar plan</h1><form method="POST" action="{{ route('admin.planes.update', $plan) }}">@method('PUT')@include('admin.plans._form')</form></div>@endsection
