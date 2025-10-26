<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Mail\VerifyMail;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $users = User::all();

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        User::create($data);

        return redirect()->route('users.index')->with('success', 'User created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user): View
    {
        $user->load(['posts', 'comments', 'replies', 'profile']);

        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();

        $originalEmail = $user->email;
        $updated = $user->update($data);

        if ($updated && isset($data['email']) && $data['email'] !== $originalEmail) {
            $user->forceFill([
                'email_verified_at' => null,
                'email_verification_token' => Str::random(64),
                'email_verification_expires_at' => now()->addHour(),
            ])->save();

            // Send verification mail
            // Mail::to($user->email)->send(new VerifyMail($user));
        }

        return redirect()->route('users.show', $user)->with('success', 'User updated successfully!');

    }

    /**
     * Soft delete the specified resource.
     */
    public function destroy(User $user): RedirectResponse
    {
        $deleted = $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully!');
    }

    /**
     * Return a list of soft-deleted users.
     */
    public function deleted(): View
    {
        $deleted_users = User::onlyTrashed()->get();

        return view('users.deleted', compact('deleted_users'));
    }

    /**
     * Restore the specified soft-deleted user.
     */
    public function restore($id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $restored = $user->restore();

        return redirect()->route('users.deleted')->with(
            $restored ? 'success' : 'error',
            $restored ? 'User restored successfully!' : 'Failed to restore user.'
        );
    }

    /**
     * Permanently delete the specified user.
     */
    public function force_delete($id): RedirectResponse
    {
        $user = User::onlyTrashed()->findOrFail($id);
        $force_deleted = $user->forceDelete();

        return redirect()->route('users.deleted')->with(
            $force_deleted ? 'success' : 'error',
            $force_deleted ? 'User permanently deleted!' : 'Failed to permanently delete user.'
        );
    }

    /**
     * Activate the specified user.
     */
    public function activate($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $activated = $user->forceFill(['is_active' => true])->save();

        return redirect()
            ->route('users.index')
            ->with(
                $activated ? 'success' : 'error',
                $activated ? 'User activated successfully!' : 'Failed to activate user.'
            );
    }

    /**
     * Deactivate the specified user.
     */
    public function deactivate($id): RedirectResponse
    {
        $user = User::findOrFail($id);
        $deactivated = $user->forceFill(['is_active' => false])->save();

        return redirect()
            ->route('users.index')
            ->with(
                $deactivated ? 'success' : 'error',
                $deactivated ? 'User deactivated successfully!' : 'Failed to deactivate user.'
            );
    }
}
