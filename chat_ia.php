<?php
// Exemple théorique de l'appel API
$apiKey = "AIzaSyC_dW-k2i_nHonYlLt-d780z-7swOsabrQ";
$url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent?key=" . $apiKey;

$data = [
    "contents" => [["parts" => [["text" => "Tu es l'assistant de l'IFOSUP Running Club. Réponds à : " . $_POST['question']]]]]
];


?>