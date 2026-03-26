<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Privacy Policy | IFOSUP Running Club</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/reset.css" type="text/css">
    <link rel="stylesheet" href="css/styles.css" type="text/css">
    <style>
        .legal-content { max-width: 800px; margin: 40px auto; background: white; padding: 40px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); line-height: 1.6; }
        .legal-content h1 { color: #007bff; margin-bottom: 20px; border-bottom: 2px solid #eee; padding-bottom: 10px; }
        .legal-content h2 { color: #333; margin-top: 25px; margin-bottom: 10px; font-size: 1.2em; }
        .legal-content p { margin-bottom: 15px; color: #555; }
        .contact-info { background: #f8f9fa; padding: 20px; border-radius: 10px; border-left: 5px solid #007bff; margin-top: 30px; }
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
        <h1><i class="fa-solid fa-shield-halved"></i> Privacy Policy</h1>
        <p><strong>Dernière mise à jour :</strong> 23 mars 2026</p>

        <h2>1. Responsable du traitement</h2>
        <p>Le site est géré par <strong>Antoine</strong>, étudiant en Bac Informatique à l'IFOSUP Wavre.</p>

        <h2>2. Collecte des données</h2>
        <p>Nous collectons uniquement les données nécessaires au bon fonctionnement de l'espace membre et du classement (leaderboard) : pseudo, mot de passe haché et choix de l'avatar via l'API Robohash.</p>

        <h2>3. Utilisation et Sécurité</h2>
        <p>Vos données ne sont jamais transmises à des tiers. Elles servent exclusivement à l'affichage de vos records de survie dans l'arène de jeu. Les mots de passe sont protégés par des algorithmes de hachage modernes.</p>

        <div class="contact-info">
            <strong>Contact Editeur :</strong><br>
            Antoine (23 ans) - Étudiant IFOSUP<br>
            Avenue Baudelaire 11, 1300 Wavre<br>
            Tel : 0488 53 99 52
        </div>
    </main>

    <footer style="text-align:center; padding:20px;">
        &copy; 2026 Antoine - Projet Progressif
    </footer>
</div>
</body>
</html>