@extends('layouts.public')
@section('title', 'Nuevo plan')
@section('content')<div class="container py-5"><h1 class="mb-4">Crear plan</h1><form method="POST" action="{{ route('admin.planes.store') }}">@include('admin.plans._form')</form></div>@endsection
