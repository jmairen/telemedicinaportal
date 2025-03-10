<?php
// archivo: api.php

// API credentials
$apiName = "Bisiclet98";
$apiPassword = "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";

// Recibir la solicitud desde el frontend
$message = $_POST['message'];

// Aquí haces la llamada a la API usando la clave y el nombre de la API
$response = file_get_contents("https://api.bisiclet98.com/v1/chat?message=" . urlencode($message) . "&api_key=" . $apiPassword);

// Retornar la respuesta al frontend
echo $response;
?>
