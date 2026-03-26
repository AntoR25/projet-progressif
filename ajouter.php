<?php
// 1. SÉCURITÉ DE CONNEXION
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("connexion.php");

$message = "";
$erreur = "";

// 2. TRAITEMENT DU FORMULAIRE
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom = isset($_POST["nom"]) ? strtoupper(trim($_POST["nom"])) : ""; // Nom en majuscules
    $prenom = isset($_POST["prenom"]) ? ucfirst(trim($_POST["prenom"])) : ""; // Prénom avec 1ère lettre maj
    $temps = isset($_POST["temps"]) ? trim($_POST["temps"]) : "";
    $sexe = isset($_POST["sexe"]) ? $_POST["sexe"] : "";

    if (empty($nom) || empty($prenom) || empty($temps) || empty($sexe)) {
        $erreur = "Tous les champs sont obligatoires.";
    } 
    elseif (!preg_match("/^([0-9][0-9]):([0-5][0-9]):([0-5][0-9])$/", $temps)) {
        $erreur = "Format de temps invalide (HH:MM:SS).";
    } 
    else {
        try {
            $requete = $connexion->prepare("INSERT INTO courreur (CourNom, CourPrenom, CourTemps, CourSexe) VALUES (:nom, :prenom, :temps, :sexe)");
            if ($requete->execute([':nom' => $nom, ':prenom' => $prenom, ':temps' => $temps, ':sexe' => $sexe])) {
                $message = "Coureur ajouté avec succès !";
                echo '<script>setTimeout(function() { if(window.opener) window.opener.location.reload(); }, 1000);</script>';
            }
        } catch (PDOException $e) {
            $erreur = "Erreur : " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Coureur</title>
    <style>
        /* RESET & BASE */
        * { box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { 
            background: #f0f2f5; 
            display: flex; 
            justify-content: center; 
            align-items: center; 
            min-height: 100vh; 
            margin: 0; 
        }

        /* CARD CONTAINER */
        .card {
            background: #fff;
            padding: 2rem;
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        h2 { 
            margin-top: 0; 
            color: #333; 
            text-align: center;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }

        /* MESSAGES */
        .msg { padding: 12px; border-radius: 6px; margin-bottom: 20px; font-size: 0.9rem; text-align: center; }
        .error { background: #fee2e2; color: #b91c1c; border: 1px solid #f87171; }
        .success { background: #dcfce7; color: #15803d; border: 1px solid #4ade80; }

        /* FORM ELEMENTS */
        label { 
            display: block; 
            margin-bottom: 5px; 
            color: #666; 
            font-weight: 600; 
            font-size: 0.9rem;
        }

        input[type="text"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ddd;
            border-radius: 6px;
            transition: border-color 0.3s;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #3498db;
        }

        /* RADIO GROUP */
        .radio-group {
            display: flex;
            gap: 20px;
            margin-bottom: 25px;
            background: #f9f9f9;
            padding: 10px;
            border-radius: 6px;
        }
        .radio-item { display: flex; align-items: center; cursor: pointer; }
        .radio-item input { margin-right: 8px; }

        /* BUTTON */
        input[type="submit"] {
            width: 100%;
            background: #3498db;
            color: white;
            padding: 12px;
            border: none;
            border-radius: 6px;
            font-size: 1rem;
            font-weight: bold;
            cursor: pointer;
            transition: background 0.3s, transform 0.1s;
        }

        input[type="submit"]:hover { background: #2980b9; }
        input[type="submit"]:active { transform: scale(0.98); }

    </style>
</head>
<body>

<div class="card">
    <h2>Nouveau Coureur</h2>

    <?php if ($erreur): ?>
        <div class="msg error"><?php echo $erreur; ?></div>
    <?php endif; ?>

    <?php if ($message): ?>
        <div class="msg success"><?php echo $message; ?></div>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="nom">Nom</label>
        <input type="text" id="nom" name="nom" placeholder="Ex: DUPONT" required>

        <label for="prenom">Prénom</label>
        <input type="text" id="prenom" name="prenom" placeholder="Ex: Jean" required>

        <label for="temps">Temps (HH:MM:SS)</label>
        <input type="text" name="temps" placeholder="00:45:30" 
               pattern="([0-9][0-9]):([0-5][0-9]):([0-5][0-9])" required>

        <label>Sexe</label>
        <div class="radio-group">
            <label class="radio-item">
                <input type="radio" name="sexe" value="h" required> Homme
            </label>
            <label class="radio-item">
                <input type="radio" name="sexe" value="f" required> Femme
            </label>
        </div>

        <input type="submit" value="Ajouter au classement">
    </form>
</div>

</body>
</html>