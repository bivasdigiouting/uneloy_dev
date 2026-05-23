<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class ECardProfileController extends Controller
{
    protected function ensureAuthenticated()
    {
        if (! Auth::guard('ecard')->check()) {
            abort(403, 'Unauthorized');
        }
    }

    /**
     * Show profile page with avatar preview
     */
    public function index(Request $request)
    {
        $this->ensureAuthenticated();
        $user = Auth::guard('ecard')->user();

        $avatarUrl = null;
        $prefix = 'avatars/ecard_'.$user->id.'.';
        // Try common image extensions
        foreach (['jpg', 'jpeg', 'png', 'webp'] as $ext) {
            $candidate = $prefix.$ext;
            if (Storage::disk('public')->exists($candidate)) {
                $avatarUrl = asset('storage/'.$candidate);
                break;
            }
        }

        return view('ecard.profile.index', [
            'user' => $user,
            'avatarUrl' => $avatarUrl,
        ]);
    }

    /**
     * Handle avatar upload and replace existing file
     */
    public function storeAvatar(Request $request)
    {
        $this->ensureAuthenticated();

        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);

        $user = Auth::guard('ecard')->user();
        $file = $request->file('avatar');
        $ext = strtolower($file->getClientOriginalExtension());
        $filename = 'ecard_'.$user->id.'.'.$ext;

        // Remove any previous avatar with different extension
        foreach (['jpg', 'jpeg', 'png', 'webp'] as $prevExt) {
            $prev = 'avatars/ecard_'.$user->id.'.'.$prevExt;
            if (Storage::disk('public')->exists($prev)) {
                Storage::disk('public')->delete($prev);
            }
        }

        Storage::disk('public')->putFileAs('avatars', $file, $filename);

        return Redirect::route('ecard.profile.index')
            ->with('success', 'Profile image updated successfully.');
    }
}
