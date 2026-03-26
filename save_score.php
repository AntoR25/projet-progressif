<?php
session_start();
include("connexion.php");

if (isset($_SESSION['username']) && isset($_POST['score'])) {
    $username = $_SESSION['username'];
    $newScore = (int)$_POST['score'];

    // On vérifie le meilleur score actuel de l'utilisateur
    $stmt = $connexion->prepare("SELECT score FROM leaderboard WHERE username = ?");
    $stmt->execute([$username]);
    $currentRecord = $stmt->fetch(PDO::FETCH_OBJ);

    if (!$currentRecord) {
        // Premier score pour cet utilisateur
        $ins = $connexion->prepare("INSERT INTO leaderboard (username, score) VALUES (?, ?)");
        $ins->execute([$username, $newScore]);
    } elseif ($newScore > $currentRecord->score) {
        // On met à jour seulement si c'est un nouveau record
        $upd = $connexion->prepare("UPDATE leaderboard SET score = ? WHERE username = ?");
        $upd->execute([$newScore, $username]);
    }
}
?>