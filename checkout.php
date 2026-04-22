<?php
define('PADDLE_API_KEY', 'pdl_live_apikey_01kp9j5qzbj7jhef19ew0rt81c_7bk4YqJSm6qpvYcmtMJmPX_Auj');
define('PADDLE_PRICE_ID', 'pri_01kp9j3215tx85qwaxzxfgs96w');

$payload = json_encode([
    'items' => [[
        'price_id' => PADDLE_PRICE_ID,
        'quantity' => 1,
    ]],
]);

$ch = curl_init('https://api.paddle.com/transactions');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => $payload,
    CURLOPT_HTTPHEADER     => [
        'Authorization: Bearer ' . PADDLE_API_KEY,
        'Content-Type: application/json',
    ],
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$data = json_decode($response, true);

// checkout.url já aponta para convertedigitais.com.br/checkout.html?_ptxn=txn_xxx
if ($httpCode === 201 && isset($data['data']['checkout']['url'])) {
    header('Location: ' . $data['data']['checkout']['url']);
    exit;
}

$erro = isset($data['error']['detail']) ? $data['error']['detail'] : 'Erro desconhecido';
header('Location: https://convertedigitais.com.br/index.html?erro=' . urlencode($erro));
exit;