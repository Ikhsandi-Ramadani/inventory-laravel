@extends('base')

@section('title', 'Dashboard')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <h4 class="fw-bold py-3 mb-4">Dashboard</h4>
        <h2>Selamat Datang {{ auth()->user()->name }}</h2>
    </div>
@endsection
