<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\WebsiteSettings;
use Illuminate\Support\Facades\Http;

class NotificationService
{
    /**
     * Send notification via FCM legacy API using topics.
     */
    public function send(Notification $notification): array
    {
        $settings = WebsiteSettings::first();
        $serverKey = $settings?->firebase_server_key;
        if (! $serverKey) {
            return ['success' => false, 'message' => 'FCM Server Key not configured'];
        }

        $topic = $this->mapAudienceToTopic($notification->send_to);
        $payload = [
            'to' => '/topics/'.$topic,
            'notification' => [
                'title' => $notification->title,
                'body' => (string) ($notification->description ?? ''),
                'image' => $this->imageUrl($notification->image_path),
            ],
            'data' => [
                'notification_id' => $notification->id,
                'send_to' => $notification->send_to,
            ],
        ];

        $response = Http::withHeaders([
            'Authorization' => 'key='.$serverKey,
            'Content-Type' => 'application/json',
        ])->post('https://fcm.googleapis.com/fcm/send', $payload);

        if ($response->successful()) {
            return ['success' => true, 'message' => 'Notification sent'];
        }

        return [
            'success' => false,
            'message' => 'FCM send failed',
            'error' => $response->body(),
        ];
    }

    private function mapAudienceToTopic(string $audience): string
    {
        return match ($audience) {
            'ecard' => 'ecard',
            'ecard_seva' => 'ecard_seva',
            'vendor' => 'vendor',
            default => 'general',
        };
    }

    private function imageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        // If stored via Storage::putFile in public disk, expose via asset('storage/...')
        return asset('storage/'.ltrim($path, '/'));
    }
}
