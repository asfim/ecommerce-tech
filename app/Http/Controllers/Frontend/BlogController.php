<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\View\View;

class BlogController extends Controller
{
    /**
     * Display a listing of the blog posts on the frontend.
     */
    public function index(): View
    {
        $posts = BlogPost::where('is_active', true)
            ->latest()
            ->paginate(6);

        return view('frontend.blogs.index', compact('posts'));
    }

    /**
     * Display the specified blog post.
     */
    public function show(string $slug): View
    {
        $post = BlogPost::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Increment post views counter
        $post->increment('views');

        // Fetch recent posts for sidebar widget
        $recentPosts = BlogPost::where('is_active', true)
            ->where('id', '!=', $post->id)
            ->latest()
            ->take(5)
            ->get();

        return view('frontend.blogs.show', compact('post', 'recentPosts'));
    }
}
