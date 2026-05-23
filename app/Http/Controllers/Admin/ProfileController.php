<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\ProfileRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    protected $profileRepository;

    public function __construct(ProfileRepository $profileRepository)
    {
        $this->profileRepository = $profileRepository;
    }

    /**
     * Show the profile settings page
     */
    public function show()
    {
        $user = Auth::guard('admin')->user();

        return view('admin.profile.settings', compact('user'));
    }

    /**
     * Update the user's profile information
     */
    public function update(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only([
            'name', 'email', 'phone', 'address',
            'city', 'state', 'country', 'postal_code',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $this->profileRepository->uploadImage($request->file('image'), $user);
        }

        $this->profileRepository->update($user->id, $data);

        return redirect()->route('admin.profile.settings')
            ->with('success', 'Profile updated successfully!');
    }

    /**
     * Update the user's password
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Check if current password is correct
        if (! Hash::check($request->current_password, $user->password)) {
            return back()->withErrors([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        $this->profileRepository->updatePassword($user->id, $request->password);

        return redirect()->route('admin.profile.settings')
            ->with('success', 'Password updated successfully!');
    }

    /**
     * Remove the user's profile image
     */
    public function removeImage()
    {
        $user = Auth::guard('admin')->user();

        if ($user->image) {
            $this->profileRepository->deleteImage($user);
            $this->profileRepository->update($user->id, ['image' => null]);
        }

        return redirect()->route('admin.profile.settings')
            ->with('success', 'Profile image removed successfully!');
    }
}
