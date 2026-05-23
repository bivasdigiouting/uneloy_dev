<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class NotificationController extends Controller
{
    /**
     * List sent notifications.
     *
     * @group Notifications
     *
     * @unauthenticated
     *
     * @queryParam send_to string Filter by audience. Allowed: ecard, ecard_seva, vendor. No-example
     * @queryParam limit integer Limit number of items returned. Defaults to 20. Example: 20
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Notifications fetched.",
     *   "data": [
     *     {
     *       "id": 1,
     *       "send_to": "ecard",
     *       "title": "Welcome",
     *       "description": "Hello world",
     *       "image_url": "http://localhost/storage/notifications/sample.jpg",
     *       "sent_at": "2025-11-05T19:20:00Z"
     *     }
     *   ]
     * }
     */
    public function index(Request $request)
    {
        $limit = (int) ($request->query('limit', 20));
        $limit = $limit > 0 && $limit <= 100 ? $limit : 20;

        $qb = Notification::query()
            ->where('is_sent', true)
            ->orderByDesc('sent_at')
            ->orderByDesc('id');

        $sendTo = $request->query('send_to');
        if ($sendTo && in_array($sendTo, ['ecard', 'ecard_seva', 'vendor'])) {
            $qb->where('send_to', $sendTo);
        }

        $rows = $qb->limit($limit)->get();

        $data = $rows->map(function (Notification $n) {
            return [
                'id' => $n->id,
                'send_to' => $n->send_to,
                'title' => $n->title,
                'description' => $n->description,
                'image_url' => $this->imageUrl($n->image_path),
                'sent_at' => optional($n->sent_at)->toIso8601String(),
            ];
        });

        return response()->json([
            'success' => true,
            'message' => 'Notifications fetched.',
            'data' => $data,
        ]);
    }

    /**
     * Get notification details.
     *
     * @group Notifications
     *
     * @unauthenticated
     *
     * @urlParam id integer required The notification ID.
     *
     * @response 200 {
     *   "success": true,
     *   "message": "Notification fetched.",
     *   "data": {
     *     "id": 1,
     *     "send_to": "ecard",
     *     "title": "Welcome",
     *     "description": "Hello world",
     *     "image_url": "http://localhost/storage/notifications/sample.jpg",
     *     "sent_at": "2025-11-05T19:20:00Z"
     *   }
     * }
     * @response 404 {"success": false, "message": "Notification not found"}
     */
    public function show($id)
    {
        $n = Notification::query()->where('is_sent', true)->find($id);
        if (! $n) {
            return response()->json([
                'success' => false,
                'message' => 'Notification not found',
            ], 404);
        }

        $data = [
            'id' => $n->id,
            'send_to' => $n->send_to,
            'title' => $n->title,
            'description' => $n->description,
            'image_url' => $this->imageUrl($n->image_path),
            'sent_at' => optional($n->sent_at)->toIso8601String(),
        ];

        return response()->json([
            'success' => true,
            'message' => 'Notification fetched.',
            'data' => $data,
        ]);
    }

    private function imageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }
        // If stored under public disk, generate accessible URL
        try {
            return Storage::url($path);
        } catch (\Throwable $e) {
            return $path; // fallback to raw path
        }
    }
}
