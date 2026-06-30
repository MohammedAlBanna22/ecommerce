<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * ═════════════════════════════════════════
     * Show User Profile
     * ═════════════════════════════════════════
     */
    public function show()
    {
        return view('profile.show', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * ═════════════════════════════════════════
     * Show Edit Profile Form
     * ═════════════════════════════════════════
     */
    public function edit()
    {
        return view('profile.edit', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * ═════════════════════════════════════════
     * Update User Profile
     * ═════════════════════════════════════════
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email,' . auth()->id(),
            ],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date', 'before:today'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if (auth()->user()->avatar && Storage::exists(auth()->user()->avatar)) {
                Storage::delete(auth()->user()->avatar);
            }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = '/storage/' . $path;
        }

        auth()->user()->update($validated);

        return redirect()
            ->route('profile.show')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * ═════════════════════════════════════════
     * Change Password
     * ═════════════════════════════════════════
     */
    public function changePassword()
    {
        return view('profile.change-password');
    }

    /**
     * ═════════════════════════════════════════
     * Update Password
     * ═════════════════════════════════════════
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => [
                'required',
                'current_password',
            ],
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ]);

        auth()->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password updated successfully!');
    }

    /**
     * ═════════════════════════════════════════
     * Security Settings
     * ═════════════════════════════════════════
     */
    public function security()
    {
        return view('profile.security', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * ═════════════════════════════════════════
     * Avatar Upload Page
     * ═════════════════════════════════════════
     */
    public function avatar()
    {
        return view('profile.avatar');
    }

    /**
     * ═════════════════════════════════════════
     * Upload Avatar (AJAX)
     * ═════════════════════════════════════════
     */
    public function uploadAvatar(Request $request)
    {
        $validated = $request->validate([
            'avatar' => ['required', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar
            if (auth()->user()->avatar && Storage::exists(auth()->user()->avatar)) {
                Storage::delete(auth()->user()->avatar);
            }

            // Store new avatar
            $path = $request->file('avatar')->store('avatars', 'public');
            auth()->user()->update([
                'avatar' => '/storage/' . $path,
            ]);

            return response()->json([
                'success' => true,
                'avatar' => auth()->user()->avatar,
                'message' => 'Avatar updated successfully!',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No file uploaded',
        ], 400);
    }

    /**
     * ═════════════════════════════════════════
     * Notifications Preferences
     * ═════════════════════════════════════════
     */
    public function notifications()
    {
        return view('profile.notifications', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * ═════════════════════════════════════════
     * Delete Account
     * ═════════════════════════════════════════
     */
    public function destroy(Request $request)
    {
        $validated = $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = auth()->user();

        // Delete user's data
        // 1. Delete avatar if exists
        if ($user->avatar && Storage::exists($user->avatar)) {
            Storage::delete($user->avatar);
        }

        // 2. Delete related data (cascade should handle this, but explicit is better)
        $user->orders()->delete();
        $user->addresses()->delete();
        $user->cart?->delete();
        $user->payments()->delete();
        $user->reviews()?->delete();

        // 3. Delete the user
        $user->delete();

        return redirect('/')
            ->with('success', 'Your account has been deleted successfully. We\'re sorry to see you go!');
    }
}