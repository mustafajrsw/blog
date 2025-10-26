<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReactionTypeRequest;
use App\Http\Requests\UpdateReactionTypeRequest;
use App\Models\ReactionType;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ReactionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $reaction_type = ReactionType::with(['reactions'])->get();

        return view('reaction_type.index', compact('reaction_type'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('reaction_type.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReactionTypeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        ReactionType::create($data);

        return redirect()->route('reaction_type.index')->with('success', 'ReactionType created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(ReactionType $ReactionType): View
    {
        $ReactionType->load(['user', 'comments', 'replies', 'reactions']);

        return view('reaction_type.show', compact('ReactionType'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReactionType $ReactionType): View
    {
        return view('reaction_type.edit', compact('ReactionType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReactionTypeRequest $request, ReactionType $ReactionType): RedirectResponse
    {
        $ReactionType->update($request->validated());

        return redirect()->route('reaction_type.show', $ReactionType)->with('success', 'ReactionType updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReactionType $ReactionType): RedirectResponse
    {
        $ReactionType->delete();

        return redirect()->route('reaction_type.index')->with('success', 'ReactionType deleted successfully!');
    }

    /**
     * Return a list of soft-deleted reaction_type.
     */
    public function deleted(): View
    {
        $reaction_type = ReactionType::onlyTrashed();

        return view('reaction_type.deleted', compact('reaction_type'));
    }

    /**
     * Restore the specified soft-deleted ReactionType to its original state.
     *
     * @param  int  $id  The id of the ReactionType to be restored.
     * @return string 'Success' if the ReactionType was successfully restored, 'Failure' otherwise.
     */
    public function restore($id): RedirectResponse
    {
        $restored = ReactionType::onlyTrashed()->where('id', $id)->restore();

        return redirect()->route('reaction_type.deleted')->with(
            $restored ? 'success' : 'error',
            $restored ? 'ReactionType restored successfully!' : 'Failed to restore ReactionType.'
        );
    }

    /**
     * Permanently delete the specified ReactionType.
     *
     * @param  int  $id  The id of the ReactionType to be permanently deleted.
     * @return string 'Success' if the ReactionType was successfully permanently deleted, 'Failure' otherwise.
     */
    public function force_delete($id): RedirectResponse
    {
        $deleted = ReactionType::onlyTrashed()->where('id', $id)->forceDelete();

        return redirect()->route('reaction_type.deleted')->with(
            $deleted ? 'success' : 'error',
            $deleted ? 'ReactionType permanently deleted!' : 'Failed to permanently delete ReactionType.'
        );
    }
}
