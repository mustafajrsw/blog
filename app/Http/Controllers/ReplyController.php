<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReplyRequest;
use App\Http\Requests\UpdateReplyRequest;
use App\Models\Reply;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $replies = Reply::with(['user', 'comments', 'reactions'])->get();

        return view('replies.index', compact('replies'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('replies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReplyRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        Reply::create($data);

        return redirect()->route('replies.index')->with('success', 'Reply created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Reply $reply): View
    {
        $reply->load(['user', 'comments', 'replies', 'reactions']);

        return view('replies.show', compact('reply'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reply $reply): View
    {
        return view('replies.edit', compact('reply'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReplyRequest $request, Reply $reply): RedirectResponse
    {
        $reply->update($request->validated());

        return redirect()->route('replies.show', $reply)->with('success', 'Reply updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reply $reply): RedirectResponse
    {
        $reply->delete();

        return redirect()->route('replies.index')->with('success', 'Reply deleted successfully!');
    }

    /**
     * Return a list of soft-deleted replies.
     */
    public function deleted(): View
    {
        $replies = Reply::onlyTrashed();

        return view('replies.deleted', compact('replies'));
    }

    /**
     * Restore the specified soft-deleted reply to its original state.
     *
     * @param  int  $id  The id of the reply to be restored.
     * @return string 'Success' if the reply was successfully restored, 'Failure' otherwise.
     */
    public function restore($id): RedirectResponse
    {
        $restored = Reply::onlyTrashed()->where('id', $id)->restore();

        return redirect()->route('replies.deleted')->with(
            $restored ? 'success' : 'error',
            $restored ? 'Reply restored successfully!' : 'Failed to restore reply.'
        );
    }

    /**
     * Permanently delete the specified reply.
     *
     * @param  int  $id  The id of the reply to be permanently deleted.
     * @return string 'Success' if the reply was successfully permanently deleted, 'Failure' otherwise.
     */
    public function force_delete($id): RedirectResponse
    {
        $deleted = Reply::onlyTrashed()->where('id', $id)->forceDelete();

        return redirect()->route('replies.deleted')->with(
            $deleted ? 'success' : 'error',
            $deleted ? 'Reply permanently deleted!' : 'Failed to permanently delete reply.'
        );
    }
}
