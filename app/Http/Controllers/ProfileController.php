<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProfileRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\Profile;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $profiles = Profile::with(['user'])->get();

        return view('profiles.index', compact('profiles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('profiles.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProfileRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        Profile::create($data);

        return redirect()->route('profiles.index')->with('success', 'Profile created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Profile $profile): View
    {
        $profile->load(['user']);

        return view('profiles.show', compact('profile'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Profile $profile): View
    {
        return view('profiles.edit', compact('profile'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProfileRequest $request, Profile $profile): RedirectResponse
    {
        $profile->update($request->validated());

        return redirect()->route('profiles.show', $profile)->with('success', 'Profile updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Profile $profile): RedirectResponse
    {
        $profile->delete();

        return redirect()->route('profiles.index')->with('success', 'Profile deleted successfully!');
    }

    /**
     * Return a list of soft-deleted profiles.
     */
    public function deleted(): View
    {
        $profiles = Profile::onlyTrashed();

        return view('profiles.deleted', compact('profiles'));
    }

    /**
     * Restore the specified soft-deleted Profile to its original state.
     *
     * @param  int  $id  The id of the Profile to be restored.
     * @return string 'Success' if the Profile was successfully restored, 'Failure' otherwise.
     */
    public function restore($id): RedirectResponse
    {
        $restored = Profile::onlyTrashed()->where('id', $id)->restore();

        return redirect()->route('profiles.deleted')->with(
            $restored ? 'success' : 'error',
            $restored ? 'Profile restored successfully!' : 'Failed to restore Profile.'
        );
    }

    /**
     * Permanently delete the specified Profile.
     *
     * @param  int  $id  The id of the Profile to be permanently deleted.
     * @return string 'Success' if the Profile was successfully permanently deleted, 'Failure' otherwise.
     */
    public function force_delete($id): RedirectResponse
    {
        $deleted = Profile::onlyTrashed()->where('id', $id)->forceDelete();

        return redirect()->route('profiles.deleted')->with(
            $deleted ? 'success' : 'error',
            $deleted ? 'Profile permanently deleted!' : 'Failed to permanently delete Profile.'
        );
    }
}
