<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class ReviewController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:view-reviews,admin', only: ['index', 'show']),
            new Middleware('permission:delete-reviews,admin', only: ['destroy']),
        ];
    }

    public function index()
    {
        $reviews = Review::with('product')->latest()->paginate(15);

        return view('backend.reviews.index', compact('reviews'));
    }

    public function destroy(Review $review)
    {
        $review->delete();

        return back()->with('success', 'Review deleted successfully.');
    }
}
