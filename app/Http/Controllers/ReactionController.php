<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReactionRequest;
use App\Http\Requests\UpdateReactionRequest;
use App\Models\Reaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ReactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $reactions = Reaction::all();

        return view('reactions.index', compact('reactions'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('reactions.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReactionRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $reaction = Reaction::create($data);

        return redirect()
            ->route('reactions.index')
            ->with(
                $reaction ? 'success' : 'error',
                $reaction ? 'Reaction created successfully!' : 'Failed to create reaction.'
            );
    }

    /**
     * Display the specified resource.
     */
    public function show(Reaction $reaction): View
    {
        return view('reactions.show', compact('reaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reaction $reaction): View
    {
        return view('reactions.edit', compact('reaction'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReactionRequest $request, Reaction $reaction): RedirectResponse
    {
        $data = $request->validated();
        $updated = $reaction->update($data);

        return redirect()
            ->route('reactions.index')
            ->with(
                $updated ? 'success' : 'error',
                $updated ? 'Reaction updated successfully!' : 'Failed to update reaction.'
            );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reaction $reaction): RedirectResponse
    {
        $deleted = $reaction->delete();

        return redirect()
            ->route('reactions.index')
            ->with(
                $deleted ? 'success' : 'error',
                $deleted ? 'Reaction deleted successfully!' : 'Failed to delete reaction.'
            );
    }
}
