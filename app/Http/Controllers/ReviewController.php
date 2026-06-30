<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of user's reviews
     */
    public function index()
    {
        $reviews = auth()->user()
            ->reviews()
            ->with('product')
            ->latest()
            ->paginate(15);

        return view('reviews.index', compact('reviews'));
    }

    /**
     * Show a specific review
     */
    public function show($id)
    {
        $review = auth()->user()
            ->reviews()
            ->with('product')
            ->findOrFail($id);

        return view('reviews.show', compact('review'));
    }

    /**
     * Show the form for editing a review
     */
    public function edit($id)
    {
        $review = auth()->user()
            ->reviews()
            ->findOrFail($id);

        return view('reviews.edit', compact('review'));
    }

    /**
     * Update a review
     */
    public function update(Request $request, $id)
    {
        $review = auth()->user()
            ->reviews()
            ->findOrFail($id);

        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:2000',
            'is_verified_purchase' => 'boolean',
        ]);

        $review->update($validated);

        return redirect()
            ->route('reviews.show', $review)
            ->with('success', 'Review updated successfully!');
    }

    /**
     * Delete a review
     */
    public function destroy($id)
    {
        $review = auth()->user()
            ->reviews()
            ->findOrFail($id);

        $review->delete();

        return redirect()
            ->route('reviews.index')
            ->with('success', 'Review deleted successfully!');
    }
}
