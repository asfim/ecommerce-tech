@extends('layouts.app')

@section('title', $post->title . ' - Blog')

@section('content')
<div class="wrap py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ route('blogs.index') }}" class="text-decoration-none text-muted">Blog</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ Str::limit($post->title, 35) }}</li>
        </ol>
    </nav>

    <!-- Main Grid -->
    <div class="row g-4">
        <!-- Article Content -->
        <div class="col-12 col-lg-8">
            <article class="bg-white p-4 p-md-5 rounded-4 shadow-sm">
                <!-- Back Link -->
                <a href="{{ route('blogs.index') }}" class="btn btn-link text-decoration-none p-0 mb-4 text-muted hover-blue d-inline-flex align-items-center gap-1">
                    <i class="bi bi-arrow-left"></i> Back to Blog
                </a>

                <!-- Meta info -->
                <div class="text-muted small mb-3 d-flex flex-wrap align-items-center gap-3">
                    <span><i class="bi bi-calendar3 me-1"></i> Published: {{ $post->created_at->format('F d, Y') }}</span>
                    <span>•</span>
                    <span><i class="bi bi-clock me-1"></i> {{ max(3, ceil(str_word_count(strip_tags($post->body)) / 200)) }} min read</span>
                    <span>•</span>
                    <span><i class="bi bi-eye me-1"></i> {{ number_format($post->views) }} views</span>
                </div>

                <!-- Title -->
                <h1 class="fw-bold mb-4 display-6 text-dark leading-tight">{{ $post->title }}</h1>

                <!-- Featured Image -->
                <div class="mb-4 overflow-hidden rounded-4 border border-light" style="max-height: 480px;">
                    @if($post->image)
                        <img src="{{ asset('storage/' . $post->image) }}" class="w-100" style="object-fit: cover; max-height: 480px;" alt="{{ $post->title }}">
                    @else
                        <div class="bg-light d-flex align-items-center justify-content-center text-muted" style="height: 350px;">
                            <i class="bi bi-image" style="font-size: 72px; opacity: 0.2;"></i>
                        </div>
                    @endif
                </div>

                <!-- Summary Callout -->
                <div class="p-3 bg-light rounded-3 border-start border-primary border-4 mb-4 font-italic text-muted">
                    {{ $post->summary }}
                </div>

                <!-- Body (HTML Content) -->
                <div class="article-body lh-lg text-secondary">
                    {!! $post->body !!}
                </div>

                <!-- Social Share Widget (Decorative) -->
                <hr class="my-5">
                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                    <span class="fw-bold text-dark small">Share this article:</span>
                    <div class="d-flex gap-2">
                        <button class="btn btn-outline-secondary btn-sm rounded-circle" style="width: 32px; height: 32px; padding:0; display: inline-flex; align-items:center; justify-content:center;"><i class="bi bi-facebook"></i></button>
                        <button class="btn btn-outline-secondary btn-sm rounded-circle" style="width: 32px; height: 32px; padding:0; display: inline-flex; align-items:center; justify-content:center;"><i class="bi bi-twitter-x"></i></button>
                        <button class="btn btn-outline-secondary btn-sm rounded-circle" style="width: 32px; height: 32px; padding:0; display: inline-flex; align-items:center; justify-content:center;"><i class="bi bi-linkedin"></i></button>
                        <button class="btn btn-outline-secondary btn-sm rounded-circle" style="width: 32px; height: 32px; padding:0; display: inline-flex; align-items:center; justify-content:center;"><i class="bi bi-link-45deg"></i></button>
                    </div>
                </div>
            </article>
        </div>

        <!-- Sidebar Widgets -->
        <div class="col-12 col-lg-4">
            <!-- Recent Posts Widget -->
            <div class="bg-white p-4 rounded-4 shadow-sm mb-4">
                <h5 class="fw-bold mb-3 pb-2 border-bottom text-dark">Recent Articles</h5>
                <div class="d-flex flex-column gap-3">
                    @forelse($recentPosts as $rp)
                        <div class="d-flex gap-3 align-items-center">
                            <div class="flex-shrink-0 overflow-hidden rounded border border-light" style="width: 64px; height: 64px; aspect-ratio: 1/1;">
                                @if($rp->image)
                                    <img src="{{ asset('storage/' . $rp->image) }}" class="w-100 h-100" style="object-fit: cover;" alt="">
                                @else
                                    <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted">
                                        <i class="bi bi-image" style="font-size: 16px; opacity: 0.4;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-grow-1 min-w-0">
                                <a href="{{ route('blogs.show', $rp->slug) }}" class="text-dark fw-semibold text-truncate-2 text-decoration-none hover-blue d-block small" style="line-height: 1.3;">
                                    {{ $rp->title }}
                                </a>
                                <span class="text-muted small" style="font-size: 11px;"><i class="bi bi-calendar3 me-1"></i> {{ $rp->created_at->format('M d, Y') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-muted small">No other recent articles.</div>
                    @endforelse
                </div>
            </div>

            <!-- Shop Promo Widget -->
            <div class="p-4 rounded-4 shadow-sm text-white position-relative overflow-hidden" style="background: linear-gradient(135deg, #1f2937, #111827);">
                <div class="position-absolute" style="right: -20px; bottom: -20px; opacity: 0.1; font-size: 120px;">
                    <i class="bi bi-bag-heart"></i>
                </div>
                <div class="position-relative">
                    <h5 class="fw-bold mb-2">Explore Our Store</h5>
                    <p class="small text-white-50 mb-4">Discover exclusive arrivals, hot trends, and premium tech deals with worldwide delivery.</p>
                    <a href="{{ route('home') }}" class="btn btn-primary btn-sm w-100 fw-bold rounded-pill py-2">
                        Start Shopping Now <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .article-body h1, .article-body h2, .article-body h3, .article-body h4, .article-body h5, .article-body h6 {
        color: #212529;
        font-weight: 700;
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    .article-body p {
        margin-bottom: 1.5rem;
    }
    .text-truncate-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;  
        overflow: hidden;
    }
</style>
@endsection
