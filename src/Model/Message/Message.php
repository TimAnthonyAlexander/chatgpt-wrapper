<?php

declare(strict_types=1);

namespace Timanthonyalexander\Chatgptwrapper\Model\Message;

use Timanthonyalexander\Chatgptwrapper\Model\Data\DataModel;

class Message extends DataModel
{
    public string $role;
    public string $content = '';
    public string $created;

    public static function getRuleText(): Message
    {
        $file = __DIR__ . '/../../../config/general.txt';
        $text = (string) file_get_contents($file);

        $message = new Message();
        $message->role = 'system';
        $message->content = $text;
        $message->created = date('Y-m-d H:i:s');

        return $message;
    }

    public static function getMessagesByUser(string $user): array
    {
        $file = __DIR__ . '/../../../data/' . $user . '.json';
        if (!file_exists($file)) {
            $data = [[
                'role' => 'assistant',
                'content' => 'Welcome to ChatGPT-Wrapper. How may I help you?',
                'created' => date('Y-m-d H:i:s'),
            ]];
            file_put_contents($file, json_encode($data, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
        }
        $messages = json_decode((string) file_get_contents($file), true);
        if (!is_array($messages)) {
            throw new \RuntimeException('Could not read messages from file ' . $file);
        }

        $messageObjects = [];

        foreach ($messages as $message) {
            $messageObject = new Message();
            $messageObject->role = $message['role'];
            $messageObject->content = $message['content'];
            $messageObject->created = $message['created'];
            $messageObjects[] = $messageObject;
        }

        return $messageObjects;
    }

    public function saveToUser(string $user): void
    {
        $file = __DIR__ . '/../../../data/' . $user . '.json';

        if (!file_exists($file)) {
            file_put_contents($file, '{}');
        }

        $messages = json_decode((string) file_get_contents($file), true);
        if (!is_array($messages)) {
            $messages = [];
        }

        $this->created = date('Y-m-d H:i:s');
        $messages[] = $this->toArray();

        file_put_contents($file, json_encode($messages, JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT));
    }

    public function toArrayFiltered(): array
    {
        return [
            'role' => $this->role,
            'content' => $this->content,
        ];
    }
}
