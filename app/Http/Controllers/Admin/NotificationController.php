<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::orderByDesc('created_at')->paginate(15);

        return view('admin.notifications.index', compact('notifications'));
    }

    public function create()
    {
        return view('admin.notifications.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(Notification::validationRules());

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = Storage::disk('public')->putFile('notifications', $request->file('image'));
        }

        $notification = Notification::create([
            'send_to' => $validated['send_to'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'image_path' => $imagePath,
            'is_sent' => false,
        ]);

        return redirect()->route('admin.notifications.index')->with('success', 'Notification created.');
    }

    public function edit(Notification $notification)
    {
        return view('admin.notifications.edit', compact('notification'));
    }

    public function update(Request $request, Notification $notification)
    {
        $validated = $request->validate(Notification::validationRules());
        if ($request->hasFile('image')) {
            // delete old image if exists
            if ($notification->image_path) {
                Storage::disk('public')->delete($notification->image_path);
            }
            $notification->image_path = Storage::disk('public')->putFile('notifications', $request->file('image'));
        }
        $notification->send_to = $validated['send_to'];
        $notification->title = $validated['title'];
        $notification->description = $validated['description'] ?? null;
        $notification->save();

        return redirect()->route('admin.notifications.index')->with('success', 'Notification updated.');
    }

    public function destroy(Notification $notification)
    {
        if ($notification->image_path) {
            Storage::disk('public')->delete($notification->image_path);
        }
        $notification->delete();

        return redirect()->route('admin.notifications.index')->with('success', 'Notification deleted.');
    }

    public function send(Notification $notification, NotificationService $service)
    {
        $result = $service->send($notification);
        if ($result['success'] ?? false) {
            $notification->is_sent = true;
            $notification->sent_at = now();
            $notification->save();

            return redirect()->route('admin.notifications.index')->with('success', 'Notification sent successfully.');
        }

        return redirect()->route('admin.notifications.index')->with('error', 'Failed to send: '.($result['message'] ?? 'Unknown error'));
    }
}
