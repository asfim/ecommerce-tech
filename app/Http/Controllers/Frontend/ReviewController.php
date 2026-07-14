<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request, Product $product)
    {
        if (! auth()->check()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'You must be logged in to write a review.',
                ], 401);
            }

            return redirect()->route('user.login')->with('error', 'Please log in to write a review.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $validated['name'] = auth()->user()->name;

        $review = $product->reviews()->create($validated);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Review submitted successfully!',
                'review' => [
                    'name' => $review->name,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'created_at' => $review->created_at->diffForHumans(),
                ],
            ]);
        }

        return back()->with('success', 'Review submitted successfully!');
    }
}
