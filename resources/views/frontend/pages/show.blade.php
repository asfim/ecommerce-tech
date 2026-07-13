@extends('layouts.app')

@section('title', $page->title)

@section('content')
<div class="wrap py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
        </ol>
    </nav>

    <!-- Content Card -->
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="bg-white p-4 p-md-5 rounded-4 shadow-sm">
                <!-- Title -->
                <h1 class="fw-bold mb-4 display-6 text-dark leading-tight">{{ $page->title }}</h1>
                
                <hr class="mb-4">

                <!-- Page Content -->
                <div class="page-body lh-lg text-secondary">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
