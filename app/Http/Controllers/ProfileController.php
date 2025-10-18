<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Http\Resources\ProfileResource;
use App\Models\Profile;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $this->authorize('viewAny', Profile::class);
        $profiles = Profile::all();
        $json_profiles = ProfileResource::collection($profiles);

        return $this->success($json_profiles);
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
    public function store(StoreProfileRequest $request): JsonResponse
    {
        $this->authorize('create', Profile::class);
        $data = $request->validated();
        $check = Profile::where('user_id', $request->user()->id)->first();
        if ($check) {
            return $this->fail('Profile already exists for this user.');
        }
        $data['user_id'] = $request->user()->id;
        $added = Profile::create($data);

        return $added ? $this->success() : $this->fail();
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile): JsonResponse
    {
        $this->authorize('view', $profile);
        $profile->load(['user']);
        $profile_json = ProfileResource::make($profile);

        return $this->success($profile_json);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profile $profile)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, Profile $profile): JsonResponse
    {
        $this->authorize('update', $profile);
        $data = $request->validated();
        $updated = $profile->update($data);

        return $updated ? $this->success() : $this->fail();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile): JsonResponse
    {
        $this->authorize('delete', $profile);
        $deleted = $profile->delete();

        return $deleted ? $this->success() : $this->fail();
    }

    /**
     * Return a list of soft-deleted profiles.
     */
    public function deleted(): JsonResponse
    {
        dd(2);
        $this->authorize('viewAny', Profile::class);
        $deleted_profiles = Profile::query()->onlyTrashed()->get();
        $json_profiles = ProfileResource::collection($deleted_profiles);

        return $this->success($json_profiles);
    }

    /**
     * Restore the specified soft-deleted profile to its original state.
     *
     * @param  int  $id  The id of the profile to be restored.
     * @return string 'Success' if the profile was successfully restored, 'Failure' otherwise.
     */
    public function restore($id): JsonResponse
    {
        $this->authorize('restore', Profile::class);
        $restored = Profile::query()->onlyTrashed()->where('id', $id)->restore();

        return $restored ? $this->success() : $this->fail();
    }

    /**
     * Permanently delete the specified profile.
     *
     * @param  int  $id  The id of the profile to be permanently deleted.
     * @return string 'Success' if the profile was successfully permanently deleted, 'Failure' otherwise.
     */
    public function force_delete($id): JsonResponse
    {
        $this->authorize('forceDelete', Profile::class);
        $force_deleted = Profile::query()->onlyTrashed()->where('id', $id)->forceDelete();

        return $force_deleted ? $this->success() : $this->fail();
    }
}
