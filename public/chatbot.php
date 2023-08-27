<?php

declare(strict_types=1);

namespace chatteropublic;

use Timanthonyalexander\Chatgptwrapper\Model\Message\Message;
use Timanthonyalexander\Chatgptwrapper\Module\Chatbot\Chatbot;

require_once __DIR__ . '/../vendor/autoload.php';

// Ask for a user id in stdin
echo 'Enter a user id: ';
$userId = trim(fgets(STDIN));
$chatBot = new Chatbot($userId);

while (true) {
    // Ask for a message to ask the chatbot
    echo 'Enter a message: ';
    $message = trim(fgets(STDIN));

    // Create a message object
    $request = new Message();
    $request->role = 'user';
    $request->content = $message;
    $request->created = date('Y-m-d H:i:s');
    $request->saveToUser($userId);

    // Get a response from the chatbot
    $chatBot->send($request);
    $response = $chatBot->response;

    // Print the response
    echo "Response: {$response->content}\n\n";
}
