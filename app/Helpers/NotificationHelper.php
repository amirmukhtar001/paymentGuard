<?php

namespace App\Helpers;

class NotificationHelper
{
    public static function sendToUser($userId, $title, $body, $data = [], $options = []): bool
    {
        return true;
    }

    public static function sendToUsers(array $userIds, $title, $body, $data = [], $options = []): bool
    {
        return true;
    }

    public static function sendToApp($appName, $title, $body, $data = [], $options = []): bool
    {
        return true;
    }

    public static function sendToModel($model, $title, $body, $data = [], $options = []): bool
    {
        return true;
    }

    public static function sendToTokens(array $tokens, $title, $body, $data = [], $options = []): bool
    {
        return true;
    }

    public static function sendTest($token, $title = 'Test Notification', $body = 'This is a test notification'): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public static function getStats($userId = null, $appName = null): array
    {
        return [];
    }
}

if (! function_exists('pushNotification')) {
    function pushNotification($target, $title, $body, $data = [], $options = []): bool
    {
        if (is_numeric($target)) {
            return NotificationHelper::sendToUser($target, $title, $body, $data, $options);
        }
        if (is_array($target)) {
            return NotificationHelper::sendToUsers($target, $title, $body, $data, $options);
        }
        if (is_object($target)) {
            return NotificationHelper::sendToModel($target, $title, $body, $data, $options);
        }
        if (is_string($target)) {
            return NotificationHelper::sendToApp($target, $title, $body, $data, $options);
        }

        return false;
    }
}

if (! function_exists('pushToUser')) {
    function pushToUser($userId, $title, $body, $data = [], $options = []): bool
    {
        return NotificationHelper::sendToUser($userId, $title, $body, $data, $options);
    }
}

if (! function_exists('pushToApp')) {
    function pushToApp($appName, $title, $body, $data = [], $options = []): bool
    {
        return NotificationHelper::sendToApp($appName, $title, $body, $data, $options);
    }
}

if (! function_exists('pushToModel')) {
    function pushToModel($model, $title, $body, $data = [], $options = []): bool
    {
        return NotificationHelper::sendToModel($model, $title, $body, $data, $options);
    }
}
