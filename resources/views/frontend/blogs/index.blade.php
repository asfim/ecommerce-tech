@extends('layouts.app')

@section('title', 'Blog & Articles - eCommerce Store')

@section('content')
<div class="wrap py-4">
    <!-- Breadcrumbs -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-muted">Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Blog</li>
        </ol>
    </nav>

    <!-- Blog Header Banner -->
    <div class="p-5 text-white mb-5 rounded-4 shadow-sm position-relative overflow-hidden" style="background: linear-gradient(135deg, #0d6efd, #0b5ed7);">
        <div class="position-absolute" style="right: -50px; bottom: -50px; opacity: 0.1; font-size: 200px; font-weight: 900; line-height: 1;">
            <i class="bi bi-journal-text"></i>
        </div>
        <div class="row align-items-center position-relative">
            <div class="col-lg-8">
                <span class="badge bg-white text-primary mb-2 px-3 py-2 rounded-pill fw-bold text-uppercase" style="font-size: 11px; letter-spacing: 1px;">Insights & Guides</span>
                <h1 class="fw-bold display-5 mb-2">The eCommerce Blog</h1>
                <p class="lead text-white-50 mb-0">Learn tips, industry secrets, and trends to scale your online shopping experiences.</p>
            </div>
        </div>
    </div>

    <!-- Blog Grid -->
    <div class="row g-4 mb-5">
        @forelse($posts as $post)
            <div class="col-12 col-md-6 col-lg-4">
                <article class="card h-100 border-0 shadow-sm overflow-hidden blog-card">
                    <div class="position-relative overflow-hidden" style="aspect-ratio: 16/10;">
                        @if($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}" class="card-img-top w-100 h-100 blog-img" style="object-fit: cover; transition: transform 0.4s ease;" alt="{{ $post->title }}">
                        @else
                            <div class="w-100 h-100 bg-light d-flex align-items-center justify-content-center text-muted" style="transition: transform 0.4s ease;">
                                <i class="bi bi-image" style="font-size: 48px; opacity: 0.3;"></i>
                            </div>
                        @endif
                        <span class="position-absolute top-3 start-3 badge bg-dark text-white rounded-pill px-2.5 py-1.5" style="font-size: 10px; font-weight: 500;">
                            <i class="bi bi-eye me-1"></i> {{ number_format($post->views) }} views
                        </span>
                    </div>
                    <div class="card-body d-flex flex-column p-4">
                        <div class="text-muted small mb-2 d-flex align-items-center gap-2">
                            <span><i class="bi bi-calendar3 me-1"></i> {{ $post->created_at->format('M d, Y') }}</span>
                            <span>•</span>
                            <span><i class="bi bi-clock me-1"></i> {{ max(3, ceil(str_word_count(strip_tags($post->body)) / 200)) }} min read</span>
                        </div>
                        <h5 class="card-title fw-bold mb-2">
                            <a href="{{ route('blogs.show', $post->slug) }}" class="text-dark text-decoration-none hover-blue blog-title-link">
                                {{ Str::limit($post->title, 65) }}
                            </a>
                        </h5>
                        <p class="card-text text-muted small mb-4 flex-grow-1">
                            {{ Str::limit($post->summary, 120) }}
                        </p>
                        <div class="mt-auto">
                            <a href="{{ route('blogs.show', $post->slug) }}" class="fw-semibold text-primary text-decoration-none d-inline-flex align-items-center gap-1 hover-arrow">
                                Read Full Article <i class="bi bi-arrow-right" style="transition: transform 0.2s ease;"></i>
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <div class="text-muted py-4">
                    <i class="bi bi-journal-x" style="font-size: 48px;"></i>
                    <p class="mt-2 mb-0">No blog posts available at the moment. Please check back later!</p>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $posts->links() }}
    </div>
</div>

<style>
    .blog-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border-radius: 12px;
    }
    .blog-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08) !important;
    }
    .blog-card:hover .blog-img {
        transform: scale(1.05);
    }
    .blog-title-link {
        transition: color 0.2s ease;
    }
    .blog-title-link:hover {
        color: #0d6efd !important;
    }
    .hover-arrow:hover i {
        transform: translateX(4px);
    }
    .top-3 {
        top: 1rem;
    }
    .start-3 {
        start: 1rem;
    }
</style>
@endsection
