<?php

declare(strict_types=1);

namespace Timanthonyalexander\Chatgptwrapper\Module\Chatbot;

use JsonException;
use Timanthonyalexander\Chatgptwrapper\Model\Message\Message;

class Chatbot
{
    public const MODEL = 'gpt-3.5-turbo-0301';
    public const API   = 'https://api.openai.com/v1/chat/completions';

    public Message $response;

    public function __construct(
        private string $user,
    ) {
    }

    public function send(
        Message $request,
    ): void {
        $ruleText = Message::getRuleText();
        $history = Message::getMessagesByUser($this->user);
        $newHistory = [$ruleText, ...$history, $request];

        $data = [
            'model' => self::MODEL,
            'messages' => array_map(
                fn (Message $message) => $message->toArrayFiltered(),
                $newHistory,
            ),
            'max_tokens' => 800,
            'temperature' => 0.9,
            'top_p' => 1,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
            'stop' => ['\r', '\n'],
        ];

        $json = json_encode($data, JSON_THROW_ON_ERROR);

        $curl = curl_init();

        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => self::API,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $json,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Authorization: Bearer ' . trim(file_get_contents(__DIR__ . '/../../../config/openai.txt')),
                ],
            ]
        );

        $response = curl_exec($curl);

        curl_close($curl);

        $response = trim((string) $response);

        try {
            $decoded = json_decode($response, true, 512, JSON_THROW_ON_ERROR | JSON_INVALID_UTF8_IGNORE);
        } catch (JsonException) {
            $decoded = [];
        }

        if (!isset($decoded['choices'][0]['message']['content'])) {
            print($decoded['error']['message'] ?? 'Could not detect error.');
            die();
        } else {
            $response = $decoded['choices'][0]['message']['content'];
        }

        $this->response = new Message();
        $this->response->role = 'assistant';
        $this->response->content = $response;
        $this->response->saveToUser($this->user);
    }
}
