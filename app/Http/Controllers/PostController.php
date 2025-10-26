<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $posts = Post::with(['post_status', 'user', 'comments', 'reactions'])->get();

        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePostRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        Post::create($data);

        return redirect()->route('posts.index')->with('success', 'Post created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post): View
    {
        $post->load(['user', 'comments', 'replies', 'reactions']);

        return view('posts.show', compact('post'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post): View
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $post->update($request->validated());

        return redirect()->route('posts.show', $post)->with('success', 'Post updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post): RedirectResponse
    {
        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully!');
    }

    /**
     * Return a list of soft-deleted posts.
     */
    public function deleted(): View
    {
        $deleted_posts = Post::onlyTrashed();

        return view('posts.deleted', compact('deleted_posts'));
    }

    /**
     * Restore the specified soft-deleted post to its original state.
     *
     * @param  int  $id  The id of the post to be restored.
     * @return string 'Success' if the post was successfully restored, 'Failure' otherwise.
     */
    public function restore($id): RedirectResponse
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $restored = $post->restore();

        return redirect()->route('posts.deleted')->with(
            $restored ? 'success' : 'error',
            $restored ? 'Post restored successfully!' : 'Failed to restore post.'
        );
    }

    /**
     * Permanently delete the specified post.
     *
     * @param  int  $id  The id of the post to be permanently deleted.
     * @return string 'Success' if the post was successfully permanently deleted, 'Failure' otherwise.
     */
    public function force_delete($id): RedirectResponse
    {
        $post = Post::onlyTrashed()->findOrFail($id);
        $force_deleted = $post->forceDelete();

        return redirect()->route('posts.deleted')->with(
            $force_deleted ? 'success' : 'error',
            $force_deleted ? 'Post permanently deleted!' : 'Failed to permanently delete post.'
        );
    }
}
