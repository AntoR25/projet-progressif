<?php
// On empêche l'affichage d'erreurs PHP qui pourraient corrompre le JSON
error_reporting(0);
ini_set('display_errors', 0);

// On démarre la session (nécessaire pour le NUKE et les avatars)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header('Content-Type: application/json');

// --- 1. LOGIQUE DU NUKE (ACTION SPÉCIALE) ---
if (isset($_GET['action']) && $_GET['action'] == 'nuke') {
    if (session_status() === PHP_SESSION_NONE) { session_start(); }

    // A. On calcule l'heure de fin du blocage
    $nuke_time = time() + 180; // 5 minutes

    /** * ASTUCE : Comme on va détruire la session (logout), 
     * on ne peut pas stocker 'nuke_until' dedans. 
     * On va utiliser un COOKIE qui restera sur le navigateur.
     */
    setcookie('site_nuke_timer', $nuke_time, $nuke_time, "/"); 

    // B. On détruit la session (Logout effectif)
    $_SESSION = array();
    session_destroy();

    // C. On répond au JavaScript
    echo json_encode(['status' => 'nuke_initiated', 'until' => $nuke_time]);
    exit;
}

// --- 2. LOGIQUE DE L'IA GEMINI ---

// Ta configuration
$apiKey = "AIzaSyC_dW-k2i_nHonYlLt-d780z-7swOsabrQ";
$url = "https://generativelanguage.googleapis.com/v1/models/gemini-2.5-flash:generateContent?key=" . $apiKey;

// Récupération de l'entrée JSON (depuis askAI())
$jsonInput = file_get_contents('php://input');
$input = json_decode($jsonInput, true);
$question = $input['question'] ?? '';

// Si aucune question n'est envoyée (ou accès direct au fichier)
if (empty($question)) {
    echo json_encode(['answer' => "Je suis là ! Prêt pour un footing ?"]);
    exit;
}

// Préparation des données pour l'API Gemini
$data = [
    "contents" => [
        [
            "parts" => [
                ["text" => "CONTEXTE : Tu es l'expert running de l'IFOSUP Club à Wavre. 
                RÈGLES : Réponds de façon dynamique, amicale et concise (maximum 3-4 phrases). 
                Ne coupe jamais tes phrases.
                
                QUESTION : " . $question]
            ]
        ]
    ],
    "generationConfig" => [
        "temperature" => 0.7,
        "maxOutputTokens" => 800 // Augmente ici pour laisser l'IA finir sa phrase
    ]
];

// Configuration de la requête HTTP
$options = [
    'http' => [
        'method'  => 'POST',
        'header'  => "Content-Type: application/json\r\n",
        'content' => json_encode($data),
        'ignore_errors' => true // Permet de lire le message d'erreur de Google si besoin
    ]
];

$context  = stream_context_create($options);
$response = file_get_contents($url, false, $context);

// Si le serveur Google ne répond pas du tout
if ($response === FALSE) {
    echo json_encode(['answer' => "Désolé, ma connexion est essoufflée. Réessaie plus tard !"]);
    exit;
}

$result = json_decode($response, true);

// Vérification de la structure de réponse de l'API
if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
    $answer = $result['candidates'][0]['content']['parts'][0]['text'];
    echo json_encode(['answer' => $answer]);
} 
elseif (isset($result['error'])) {
    // En cas d'erreur de clé ou de modèle (ex: ton erreur 404 précédente)
    echo json_encode(['answer' => "Note technique : " . $result['error']['message']]);
} 
else {
    // Cas imprévu (filtre de sécurité, etc.)
    echo json_encode(['answer' => "Je n'ai pas pu générer de réponse pour cette question."]);
}