<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostStatusRequest;
use App\Http\Requests\UpdatePostStatusRequest;
use App\Models\Post;
use App\Models\PostStatus;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PostStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $post_statuses = PostStatus::all();

        return view('post_statuses.index', compact('post_statuses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('post_statuses.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostStatusRequest $request): RedirectResponse
    {
        $data = $request->validated();
        PostStatus::create($data);

        return redirect()->route('post_statuses.index')->with('success', 'Post Status created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(PostStatus $postStatus)
    {
        $postStatus->load(['posts']);

        return view('post_statuses.show', compact('postStatus'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(PostStatus $postStatus): View
    {
        return view('post_statuses.edit', compact('postStatus'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostStatusRequest $request, PostStatus $postStatus): RedirectResponse
    {
        $postStatus->update($request->validated());

        return redirect()->route('post_statuses.show', $postStatus)->with('success', 'Post Status updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(PostStatus $postStatus): RedirectResponse
    {
        // Check if any posts use this status
        $hasPosts = Post::query()->where('post_status_id', $postStatus->id)->exists();

        if ($hasPosts) {
            return redirect()
                ->route('post_statuses.index')
                ->with('error', 'Cannot delete this status because it is assigned to one or more posts.');
        }

        $deleted = $postStatus->delete();

        return redirect()
            ->route('post_statuses.index')
            ->with($deleted ? 'success' : 'error', $deleted ? 'Post status deleted successfully!' : 'Failed to delete post status.');
    }

    /**
     * Return a list of soft-deleted PostStatuses.
     */
    public function deleted(): View
    {
        $deleted_post_statuses = Post::onlyTrashed();

        return view('post_statuses.deleted', compact('deleted_post_statuses'));

    }

    /**
     * Restore the specified soft-deleted Post Status to its original state.
     *
     * @param  int  $id  The id of the Post Status to be restored.
     * @return string 'Success' if the Post Status was successfully restored, 'Failure' otherwise.
     */
    public function restore($id): RedirectResponse
    {
        $restored = PostStatus::query()->onlyTrashed()->where('id', $id)->restore();

        return redirect()->route('post_statuses.deleted')->with(
            $restored ? 'success' : 'error',
            $restored ? 'Post restored successfully!' : 'Failed to restore post.'
        );

    }

    /**
     * Permanently delete the specified Post Status.
     *
     * @param  int  $id  The id of the Post Status to be permanently deleted.
     * @return string 'Success' if the Post Status was successfully permanently deleted, 'Failure' otherwise.
     */
    public function forceDelete($id): RedirectResponse
    {
        // Check if any posts still use this post status
        $hasPosts = Post::where('post_status_id', $id)->exists();

        if ($hasPosts) {
            return redirect()
                ->route('post_statuses.deleted')
                ->with('error', 'Cannot permanently delete this status because it is still assigned to one or more posts.');
        }

        $forceDeleted = PostStatus::onlyTrashed()->where('id', $id)->forceDelete();

        return redirect()
            ->route('post_statuses.deleted')
            ->with(
                $forceDeleted ? 'success' : 'error',
                $forceDeleted
                    ? 'Post status permanently deleted successfully!'
                    : 'Failed to permanently delete the post status.'
            );
    }
}
