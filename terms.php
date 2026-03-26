<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Terms of Use | IFOSUP Running Club</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/reset.css" type="text/css">
    <link rel="stylesheet" href="css/styles.css" type="text/css">
    <style>
        .legal-content { max-width: 800px; margin: 40px auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); line-height: 1.6; }
        .legal-content h1 { color: #28a745; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .legal-content h2 { color: #333; margin-top: 25px; margin-bottom: 10px; font-size: 1.2em; }
        .legal-content ul { margin-left: 20px; margin-bottom: 15px; list-style-type: square; }
    </style>
</head>
<body>
<div id="wrapper">
    <header>
        <figure id="logo">	<img src="immages/empty.png" alt="" title="" width="200" height="200">

	<figcaption>
	<h1>IFOSUP Running Club</h1>
	
	</figcaption>
	</figure>
        <nav>
            <ul>
                <li><a href="index.php">HOME</a></li>
                <li><a href="admin_panel.php">MON COMPTE</a></li>
            </ul>
        </nav>
    </header>

    <main class="legal-content">
        <h1><i class="fa-solid fa-file-contract"></i> Terms of Use</h1>
        
        <h2>1. Objet du site</h2>
        <p>Ce site est un projet académique réalisé par Antoine dans le cadre du Bac Informatique à l'IFOSUP. Il s'agit d'une plateforme de démonstration pour le cours de développement Web.</p>

        <h2>2. Comportement de l'utilisateur</h2>
        <p>En utilisant ce site, vous vous engagez à :</p>
        <ul>
            <li>Ne pas tenter de contourner les mesures de sécurité (injections SQL, XSS).</li>
            <li>Ne pas utiliser de scripts automatiques (bots) pour fausser le leaderboard de l'arène.</li>
            <li>Respecter les autres membres du club.</li>
        </ul>

        <h2>3. Propriété Intellectuelle</h2>
        <p>Le code source, les mécaniques de jeu (Classe JS ArenaGame) et le design global sont la propriété exclusive de l'auteur. L'utilisation des icônes Font Awesome et de l'API Robohash respecte les licences respectives des fournisseurs.</p>

        <h2>4. Juridiction</h2>
        <p>En cas de litige, seul le droit belge est applicable. Les bureaux de l'éditeur sont situés au 11 Avenue Baudelaire, 1300 Wavre.</p>
    </main>

    <footer style="text-align:center; padding:20px;">
        &copy; 2026 Antoine - IFOSUP Wavre
    </footer>
</div>
</body>
</html>