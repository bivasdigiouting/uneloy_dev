<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Get user profile by ID
     */
    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Update user profile information
     */
    public function updateProfile($id, array $data)
    {
        $user = $this->find($id);

        // Remove password from data if not provided
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        // Handle image upload
        if (isset($data['image']) && $data['image']) {
            // Delete old image if exists
            if ($user->image && Storage::disk('public')->exists($user->image)) {
                Storage::disk('public')->delete($user->image);
            }

            // Store new image
            $data['image'] = $data['image']->store('profile-images', 'public');
        }

        return $user->update($data);
    }

    /**
     * Update user password
     */
    public function updatePassword($id, $currentPassword, $newPassword)
    {
        $user = $this->find($id);

        // Verify current password
        if (! Hash::check($currentPassword, $user->password)) {
            return false;
        }

        return $user->update([
            'password' => Hash::make($newPassword),
        ]);
    }

    /**
     * Upload profile image
     */
    public function uploadImage($id, $image)
    {
        $user = $this->find($id);

        // Delete old image if exists
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        // Store new image
        $imagePath = $image->store('profile-images', 'public');

        $user->update(['image' => $imagePath]);

        return $imagePath;
    }

    /**
     * Delete profile image
     */
    public function deleteImage($id)
    {
        $user = $this->find($id);

        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
            $user->update(['image' => null]);

            return true;
        }

        return false;
    }
}
