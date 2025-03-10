<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json");

$api_key = "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"; 

$data = json_decode(file_get_contents("php://input"), true);
$user_message = $data["message"];

$url = "https://api.openai.com/v1/chat/completions";
$headers = [
    "Authorization: Bearer " . $api_key,
    "Content-Type: application/json"
];

$body = json_encode([
    "model" => "gpt-4o",
    "messages" => [
        ["role" => "user", "content" => $user_message]
    ]
]);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

$response = curl_exec($ch);
curl_close($ch);

echo $response;
?>
