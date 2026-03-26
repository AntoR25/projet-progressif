<?php
/**
 * Fichier de configuration de la base de données
 *
 * Cette fonction centralise la création de l'objet PDO. En cas d'échec
 * de la connexion, un message d'erreur explicite est affiché afin de
 * comprendre rapidement l'origine du problème. Pensez à adapter les
 * paramètres d'hôte, d'utilisateur et de mot de passe en fonction de
 * votre environnement local (XAMPP, Laragon, etc.).
 */
function getPDO(): PDO
{
    // Paramètres de connexion à adapter selon votre configuration
    $host = 'localhost';
    $dbname = 'bdd_projet_web';
    $user = 'root';
    $password = '';

    $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

    try {
        $pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die('Erreur de connexion à la base de données : ' . $e->getMessage());
    }
}