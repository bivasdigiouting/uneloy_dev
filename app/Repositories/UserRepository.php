<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserRepository
{
    /**
     * Get all users with their roles
     */
    public function getAllUsers()
    {
        return User::with('role')->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get paginated users with their roles
     */
    public function getPaginatedUsers($perPage = 10)
    {
        return User::with('role')->orderBy('created_at', 'desc')->paginate($perPage);
    }

    /**
     * Find user by ID with role
     */
    public function findUser($id)
    {
        return User::with('role')->findOrFail($id);
    }

    /**
     * Create a new user
     */
    public function createUser(array $data)
    {
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if (isset($data['image'])) {
            $data['image'] = $this->uploadImage($data['image']);
        }

        return User::create($data);
    }

    /**
     * Update user
     */
    public function updateUser($id, array $data)
    {
        $user = $this->findUser($id);

        if (isset($data['password']) && ! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if (isset($data['image'])) {
            // Remove old image if exists
            if ($user->image) {
                $this->removeImage($user->image);
            }
            $data['image'] = $this->uploadImage($data['image']);
        }

        $user->update($data);

        return $user->fresh('role');
    }

    /**
     * Delete user
     */
    public function deleteUser($id)
    {
        $user = $this->findUser($id);

        // Remove image if exists
        if ($user->image) {
            $this->removeImage($user->image);
        }

        return $user->delete();
    }

    /**
     * Get all roles for dropdown
     */
    public function getAllRoles()
    {
        return Role::orderBy('name')->get();
    }

    /**
     * Upload user image
     */
    private function uploadImage($image)
    {
        $filename = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
        $path = $image->storeAs('users', $filename, 'public');

        return $path;
    }

    /**
     * Remove user image
     */
    private function removeImage($imagePath)
    {
        if (Storage::disk('public')->exists($imagePath)) {
            Storage::disk('public')->delete($imagePath);
        }
    }

    /**
     * Get user image URL
     */
    public function getUserImageUrl($imagePath)
    {
        if ($imagePath && Storage::disk('public')->exists($imagePath)) {
            return Storage::url($imagePath);
        }

        return asset('backend-assets/img/default-avatar.png');
    }

    /**
     * Search users by name or email
     */
    public function searchUsers($query)
    {
        return User::with('role')
            ->where('name', 'like', '%'.$query.'%')
            ->orWhere('email', 'like', '%'.$query.'%')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get users by role
     */
    public function getUsersByRole($roleId)
    {
        return User::with('role')
            ->where('role_id', $roleId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
