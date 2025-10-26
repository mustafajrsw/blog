<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $comments = Comment::with(['user', 'replies', 'reactions'])->get();

        return view('comments.index', compact('comments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('comments.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCommentRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        Comment::create($data);

        return redirect()->route('comments.index')->with('success', 'Comment created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $Comment): View
    {
        $Comment->load(['user', 'replies', 'reactions']);

        return view('comments.show', compact('Comment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $Comment): View
    {
        return view('comments.edit', compact('Comment'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCommentRequest $request, Comment $Comment): RedirectResponse
    {
        $Comment->update($request->validated());

        return redirect()->route('comments.show', $Comment)->with('success', 'Comment updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $Comment): RedirectResponse
    {
        $Comment->delete();

        return redirect()->route('comments.index')->with('success', 'Comment deleted successfully!');
    }

    /**
     * Return a list of soft-deleted comments.
     */
    public function deleted(): View
    {
        $comments = Comment::onlyTrashed();

        return view('comments.deleted', compact('comments'));
    }

    /**
     * Restore the specified soft-deleted Comment to its original state.
     *
     * @param  int  $id  The id of the Comment to be restored.
     * @return string 'Success' if the Comment was successfully restored, 'Failure' otherwise.
     */
    public function restore($id): RedirectResponse
    {
        $restored = Comment::onlyTrashed()->where('id', $id)->restore();

        return redirect()->route('comments.deleted')->with(
            $restored ? 'success' : 'error',
            $restored ? 'Comment restored successfully!' : 'Failed to restore Comment.'
        );
    }

    /**
     * Permanently delete the specified Comment.
     *
     * @param  int  $id  The id of the Comment to be permanently deleted.
     * @return string 'Success' if the Comment was successfully permanently deleted, 'Failure' otherwise.
     */
    public function force_delete($id): RedirectResponse
    {
        $deleted = Comment::onlyTrashed()->where('id', $id)->forceDelete();

        return redirect()->route('comments.deleted')->with(
            $deleted ? 'success' : 'error',
            $deleted ? 'Comment permanently deleted!' : 'Failed to permanently delete Comment.'
        );
    }
}
