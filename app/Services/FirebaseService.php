<?php

namespace App\Services;

use Kreait\Firebase\Factory;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification;

class FirebaseService
{
    protected $messaging;

    public function __construct()
    {
        $factory = (new Factory)->withServiceAccount(env('FIREBASE_CREDENTIALS'));
        $this->messaging = $factory->createMessaging();
    }

    public function sendNotificationToMultipleUsers(array $deviceTokens, $title, $body, $data = [])
    {
        if (empty($deviceTokens)) {
            return ['error' => 'No device tokens provided'];
        }

        $message = CloudMessage::new()
            ->withNotification(Notification::create($title, $body))
            ->withData($data);

        $report = $this->messaging->sendMulticast($message, $deviceTokens);

        return [
            'success_count' => $report->successes()->count(),
            'failure_count' => $report->failures()->count(),
            'failures' => $report->failures(),
        ];
    }
}
