<?php
session_start(); 
if (isset($_COOKIE['site_nuke_timer'])) {
    if (($_COOKIE['site_nuke_timer'] - time()) > 0) {
        if (basename($_SERVER['PHP_SELF']) != 'error.php') {
            header('Location: error.php'); // Redirige vers error.php au lieu de 404.php
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>News - IFOSUP Running Club</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="icon" type="image/png" href="immages/ifosup.png">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/reset.css" type="text/css" media="screen">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
</head>

<body>

<div id="wrapper">

    <header>
        <figure id="logo">
            <img src="immages/empty.png" alt="" width="200" height="200">
            <figcaption><h1>IFOSUP Running Club</h1></figcaption>
        </figure>
        
        <nav>
            <ul>
                <li><a href="index.php"> HOME </a></li>
                <li><a href="News.php" class="lienactif"> NEWS</a></li>
                <li><a href="Results.php"> RESULTS</a></li>
                <li><a href="Contact.php"> CONTACT</a></li>

                <?php if(isset($_SESSION['username'])): ?>
                    <li class="nav-auth">
                        <a href="admin_panel.php" class="profile-link" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
                            <span>MY ACCOUNT</span>
                            <?php 
                                $nav_seed = $_SESSION['user_avatar_seed'] ?? $_SESSION['username']; 
                            ?>
                            <img src="https://robohash.org/<?php echo urlencode($nav_seed); ?>?set=set4&size=40x40" 
                                 alt="Profil" 
                                 style="border-radius: 50%; border: 1px solid #ccc; background: #eee;">
                        </a>
                    </li>
                <?php else: ?>
                    <li><a href="register.php"> REGISTER</a></li>
                    <li class="nav-auth">
                        <a href="login.php" class="login-icon" title="Connexion">
                            <i class="fa-solid fa-right-to-bracket"></i> 
                        </a>
                    </li>
                <?php endif; ?>
            </ul>   
        </nav>
    </header>

    <section>
        <?php include("connexion.php"); ?>

        <article id="content" style="width:95%; padding-left:4vw;">
            <h2>News</h2><br>

            <?php
$select = $connexion->query("SELECT * FROM articles");
while ($enregistrement = $select->fetch(PDO::FETCH_OBJ)) {
    ?>
    <div class="article-list" 
         data-aos="fade-up" 
         data-aos-duration="1000"
         style="margin-bottom: 30px; border-bottom: 1px solid #dddada; padding-bottom: 20px;">
        
        <h3 style="color: #007bff;"><?php echo htmlspecialchars($enregistrement->artTitre); ?></h3><br>
        <p><?php echo nl2br(htmlspecialchars($enregistrement->artContenu)); ?></p>
    </div>
    <?php
}
?>
        </article>
    </section>

    <footer>
    IFOSUP &copy; 2026 | 
    <a href="privacy.php" style="color: inherit; text-decoration: underline;">Privacy Policy</a> | 
    <a href="terms.php" style="color: inherit; text-decoration: underline;">Terms Of Use</a>
    </footer>

</div>
<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
  AOS.init({
      once: true // L'animation ne se joue qu'une seule fois au premier scroll
  });
</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    // Changement ici : localStorage pour que ça reste après refresh / quitter-revenir
    const isPolaire = localStorage.getItem('snow_master') === 'true' || 
                     new URLSearchParams(window.location.search).get('mode') === 'polaire';

    if (isPolaire) {
        // 1. Badge à droite du titre
        const titre = document.querySelector('article#content h2');
        if (titre) {
            titre.innerHTML += "<span style='color:#2d94b6; font-size: 0.8em; font-weight: normal; margin-left: 10px;'> (Thème Polaire)</span>";
        }

        // 2. Style "gelé" pour les articles
        const articles = document.querySelectorAll('article');
        articles.forEach(art => {
            art.style.borderLeft = "5px solid #8cd9f2";
            art.style.backgroundColor = "#f0faff";
            art.style.transition = "background-color 0.8s ease";
        });
        
        // 3. Bloc secret en bas de page
        const contentArea = document.querySelector('article#content');
        if (contentArea) {
            const secretBox = document.createElement('div');
            secretBox.innerHTML = `
                <div style="text-align:center; background:#e0f7fa; padding:15px; border-radius:10px; border:1px dashed #8cd9f2; margin-top:30px; font-style:italic; color:#0056b3;">
                    ☃️ Bravo ! Tu as fait tomber assez de neige pour geler le contenu.
                </div>
            `;
            contentArea.appendChild(secretBox);
        }

        // 4. Titres des news en bleu
        document.querySelectorAll('article h3').forEach(h3 => {
            h3.style.color = "#2d94b6";
        });

        // 5. Cas spécifique pour le TABLEAU (si on est sur Results.php)
        const tableHeaders = document.querySelectorAll('.results-table th');
        if (tableHeaders.length > 0) {
            tableHeaders.forEach(th => {
                th.style.backgroundColor = "#8cd9f2";
                th.style.color = "#0056b3";
            });
        }
    }
});
</script>
</body>
</html>