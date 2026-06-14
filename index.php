<?php

$botToken = getenv('BOT_TOKEN');
$chatId   = getenv('CHAT_ID');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data = json_decode(file_get_contents('php://input'), true);

    $message =
        "🟢 LIVEPAY DEPOSIT\n\n" .
        "User ID: " . ($data['user_id'] ?? '') . "\n" .
        "Phone: " . ($data['phone'] ?? '') . "\n" .
        "Amount: UGX " . ($data['amount'] ?? '') . "\n" .
        "Reference: " . ($data['reference'] ?? '') . "\n" .
        "Transaction ID: " . ($data['transaction_id'] ?? '') . "\n" .
        "Status: " . ($data['status'] ?? '');

    $url = "https://api.telegram.org/bot{$botToken}/sendMessage";

    $ch = curl_init($url);

    curl_setopt_array($ch, [
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POSTFIELDS => [
            'chat_id' => $chatId,
            'text' => $message
        ]
    ]);

    $response = curl_exec($ch);

    curl_close($ch);

    echo json_encode([
        'success' => true,
        'telegram' => json_decode($response, true)
    ]);

    exit;
}

echo json_encode([
    'status' => 'running'
]);
