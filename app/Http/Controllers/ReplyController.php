<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReplyRequest;
use App\Http\Requests\UpdateReplyRequest;
use App\Http\Resources\ReplyResource;
use App\Models\Reply;
use Illuminate\Http\JsonResponse;

class ReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Reply::class);
        $replies = Reply::all();
        $json_replies = ReplyResource::collection($replies);

        return $this->success($json_replies);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReplyRequest $request): JsonResponse
    {
        $this->authorize('create', Reply::class);
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $added = Reply::create($data);

        return $added ? $this->success() : $this->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(Reply $reply): JsonResponse
    {
        $this->authorize('view', $reply);
        $reply = Reply::load(['post', 'comment', 'user']);
        $replies_json = ReplyResource::make($reply);

        return $this->success($replies_json);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reply $reply)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReplyRequest $request, Reply $reply): JsonResponse
    {
        $this->authorize('update', $reply);
        $new_data = $request->validated();
        $updated = $reply->update($new_data);

        return $updated ? $this->success() : $this->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reply $reply): JsonResponse
    {
        $this->authorize('delete', $reply);
        $deleted = $reply->delete();

        return $deleted ? $this->success() : $this->fail();
    }

    /**
     * Return a list of soft-deleted replies.
     */
    public function deleted(): JsonResponse
    {
        $this->authorize('viewAny', Reply::class);
        $deleted_replies = Reply::query()->onlyTrashed()->get();
        $json_replies = ReplyResource::collection($deleted_replies);

        return $this->success($json_replies);
    }

    /**
     * Restore the specified soft-deleted reply to its original state.
     *
     * @param  int  $id  The id of the reply to be restored.
     * @return string 'Success' if the reply was successfully restored, 'Failure' otherwise.
     */
    public function restore($id): JsonResponse
    {
        $this->authorize('restore', Reply::class);
        $restored = Reply::query()->onlyTrashed()->where('id', $id)->restore();

        return $restored ? $this->success() : $this->fail();
    }

    /**
     * Permanently delete the specified reply.
     *
     * @param  int  $id  The id of the reply to be permanently deleted.
     * @return string 'Success' if the reply was successfully permanently deleted, 'Failure' otherwise.
     */
    public function force_delete($id): JsonResponse
    {
        $this->authorize('forceDelete', Reply::class);
        $force_deleted = Reply::query()->onlyTrashed()->where('id', $id)->forceDelete();

        return $force_deleted ? $this->success() : $this->fail();
    }
}
