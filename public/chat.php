<?php

declare(strict_types=1);

namespace publicchat;

require_once __DIR__ . '/../vendor/autoload.php';

use Timanthonyalexander\Chatgptwrapper\Model\Message\Message;
use Timanthonyalexander\Chatgptwrapper\Module\Chatbot\Chatbot;

# rgb(0, 0, 38)
# rgb(94, 250, 175)
# rgb(85, 222, 14)
if (!isset($_COOKIE['userid'])) {
    $userId = uniqid("", true);
    setcookie('userid', $userId, time() + 60 * 60 * 24 * 30);
} else {
    $userId = $_COOKIE['userid'];
}

$chatBot = new Chatbot($userId);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['message'])) {
    $message = $_POST['message'];

    $request = new Message();
    $request->role = 'user';
    $request->content = $message;
    $request->created = date('Y-m-d H:i:s');
    $request->saveToUser($userId);

    $chatBot->send($request);
    $response = $chatBot->response;
}

// clear cookie from post "clear" request
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['clear'])) {
    setcookie('userid', '', time() - 3600);
    header('Location: chat.php');
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>ChatGPT-Wrapper</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }

    .gradient-color {
        background: linear-gradient(to right, rgb(94, 250, 175), rgb(85, 222, 14));
        font-family: Arial, sans-serif;
        color: #ffffff;
        padding: 12px 20px;
        border: none;
        cursor: pointer;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        font-size: 16px;
        transition: opacity 0.3s, transform 0.3s;
    }

    .gradient-color:hover {
        opacity: 0.9;
    }

    .chat {
        height: 60vh;
        display: flex;
        flex-direction: column-reverse;
        overflow-y: scroll;
        scrollbar-width: none;
        -ms-overflow-style: none;
        background-color: rgb(19, 19, 57);
    }

    .chat::-webkit-scrollbar {
        display: none;
    }
</style>

<body style="background-color: rgb(0, 0, 38)">
    <h1 style="color: white; text-align: center">ChatGPT-Wrapper</h1>
    <h2 style="color: white; text-align: center">by Tim Anthony Alexander</h2>

    <div id="chatHistory" class="chat">
        <?php
        $messages = array_reverse(Message::getMessagesByUser($userId));
        foreach ($messages as $msg) {
            echo "<p style=\"color: white\"><strong>" . ucfirst($msg->role) . ": </strong> {$msg->content}</p>";
        }
        ?>
    </div>

    <form action="chat.php" method="post">
        <label for="message">Message: </label><br>
        <div style="display: flex; flex-direction: row; justify-content: space-between; align-items: center; width: 98vw;">
            <textarea type="text" id="message" name="message" rows="3" cols="90" style="width: 95%; resize: none"></textarea>
            <input type="submit" value="Send" class="gradient-color">
        </div>
    </form>
    <div style="margin-top: 30px">
        <form action="chat.php" method="post">
            <input type="hidden" name="clear" value="true">
            <input type="submit" value="Clear chat" class="gradient-color">
        </form>
    </div>
</body>

</html>
