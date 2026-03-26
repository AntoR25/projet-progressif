<?php
session_start();
include("connexion.php"); 

// --- CONFIGURATION RECAPTCHA ---
$siteKey = "6LflcJUsAAAAAJ9L-yY1w8HDUjGIHEzjB-3MUBZy";
$secretKey = "6LflcJUsAAAAAAhAfoxPf09UY0f_yoLDf5hpXrrM";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_name = trim($_POST['username']);
    $user_pass = $_POST['password'];
    
    // RÉCUPÉRATION ET HACHAGE DE L'IP (Sécurité & RGPD)
    $ip_brute = $_SERVER['REMOTE_ADDR']; 
    $user_ip_hashed = hash('sha256', $ip_brute);
    
    $captcha_response = $_POST['g-recaptcha-response'] ?? '';

    // 1. VÉRIFICATION RECAPTCHA VIA GOOGLE
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$captcha_response}");
    $responseData = json_decode($verify);

    if (empty($user_name) || empty($user_pass)) {
        $message = "Veuillez remplir tous les champs.";
        $message_type = "error";
    } 
    elseif (!$responseData || !$responseData->success) {
        $message = "La validation anti-robot a échoué.";
        $message_type = "error";
    } 
    else {
        // 2. VÉRIFICATION SI L'IP A DÉJÀ CRÉÉ UN COMPTE
        $check_ip = $connexion->prepare("SELECT id FROM utilisateurs WHERE ip_address = ?");
        $check_ip->execute([$user_ip_hashed]);
        
        // 3. VÉRIFICATION SI LE PSEUDO EST DÉJÀ PRIS
        $check_user = $connexion->prepare("SELECT id FROM utilisateurs WHERE username = ?");
        $check_user->execute([$user_name]);
        
        if ($check_ip->rowCount() > 0) {
            $message = "Un compte existe déjà pour cette connexion.";
            $message_type = "error";
        } 
        elseif ($check_user->rowCount() > 0) {
            $message = "Ce pseudo est déjà utilisé.";
            $message_type = "error";
        } 
        else {
            // 4. HACHAGE DU MOT DE PASSE ET INSERTION
            $hashed_password = password_hash($user_pass, PASSWORD_DEFAULT);
            $insert = $connexion->prepare("INSERT INTO utilisateurs (username, password, ip_address) VALUES (?, ?, ?)");
            
            if ($insert->execute([$user_name, $hashed_password, $user_ip_hashed])) {
                $message = "Inscription réussie ! Vous êtes désormais administrateur.";
                $message_type = "success";
            } else {
                $message = "Erreur lors de l'enregistrement.";
                $message_type = "error";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>Register - IFOSUP Running Club</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="icon" type="image/png" href="immages/ifosup.png">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/reset.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <style>
        body { background-color: #f4f4f4; color: #333; }

        /* Prend toute la largeur disponible dans le wrapper */
        section { 
            display: flex; 
            width: 100%; 
            min-height: 60vh;
            background: #fff; /* Fond blanc sur toute la largeur */
            border-top: 1px solid #ddd;
            border-bottom: 1px solid #ddd;
        }

        /* Colonne de gauche (Formulaire) */
        article#galerie { 
            flex: 1.5; 
            padding: 50px;
            border-right: 1px solid #eee;
        }

        /* Colonne de droite (Infos) */
        aside { 
            flex: 1; 
            padding: 50px;
            background: #fafafa; /* Légère nuance pour différencier */
        }

        /* Design Épuré des Inputs */
        .inputBox { position: relative; margin-bottom: 35px; width: 100%; max-width: 500px; }
        .inputBox input { 
            width: 100%; 
            padding: 15px 10px; 
            border: none;
            border-bottom: 2px solid #ccc; 
            font-size: 1.1rem; 
            background: transparent;
            transition: 0.3s;
        }
        .inputBox input:focus { border-bottom-color: #007bff; outline: none; }

        .inputBox span { 
            position: absolute; left: 10px; top: 15px; color: #999; 
            transition: 0.3s; pointer-events: none; text-transform: uppercase; font-size: 0.9em; 
        }
        /* Animation fluide de l'étiquette */
        .inputBox input:focus ~ span, .inputBox input:valid ~ span { 
            transform: translateY(-35px); font-size: 0.8em; color: #007bff; font-weight: bold; 
        }
        
        .enter { 
            padding: 15px 50px; background: #007bff; color: #fff; 
            border: none; border-radius: 4px; cursor: pointer; font-weight: bold; 
            text-transform: uppercase; transition: 0.3s; font-size: 1rem;
        }
        .enter:hover { background: #0056b3; letter-spacing: 1px; }

        /* Aside Content */
        aside h3 { color: #043569; margin-bottom: 20px; font-weight: bold; font-size: 1.5rem; }
        aside p { font-size: 1rem; line-height: 1.8; margin-bottom: 20px; color: #555; }
        
        /* Protection Google reCAPTCHA visible dans l'Aside */
        .google-legal-notice { 
            margin-top: 40px; 
            font-size: 0.8em; 
            color: #888; 
            padding-top: 20px; 
            border-top: 1px solid #ddd; 
        }
        .google-legal-notice a { color: #007bff; text-decoration: none; font-weight: bold; }

        /* Messages */
        .msg { padding: 15px; margin-bottom: 30px; border-radius: 4px; font-weight: bold; max-width: 500px; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }

        /* Footer Largeur Totale */
        footer { width: 100%; text-align: center; padding: 40px 0; background: #f4f4f4; }

        @media (max-width: 900px) {
            section { flex-direction: column; }
            article#galerie, aside { padding: 30px; border: none; }
        }
    </style>
</head>
<body>
<div id="wrapper">
    <header>
        <figure id="logo">
            <img src="immages/empty.png" alt="Logo" width="200" height="200">
            <figcaption><h1>IFOSUP Running Club</h1></figcaption>
        </figure>
        <nav>
            <ul>
                <li><a href="index.php"> HOME </a></li>
                <li><a href="News.php"> NEWS</a></li>
                <li><a href="Results.php"> RESULTS</a></li>
                <li><a href="Contact.php"> CONTACT</a></li>
                <li><a href="register.php" class="lienactif"> REGISTER</a></li>
            </ul>   
        </nav>   
    </header>

    <section>
        <article id="galerie">  
            <h2 style="margin-bottom: 40px; color: #222; font-size: 2.2rem;">Inscription</h2>
            
            <?php if($message): ?>
                <div class="msg <?php echo $message_type; ?>"><?php echo $message; ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="inputBox">
                    <input type="text" name="username" required="required">
                    <span>Identifiant</span>
                </div>
                <div class="inputBox">
                    <input type="password" name="password" required="required">
                    <span>Mot de passe</span>
                </div>
                
                <div style="margin-bottom: 30px;">
                    <div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div>
                </div>

                <div style="display: flex; align-items: center; gap: 20px;">
        <button type="submit" class="enter" style="width: auto;">Créer mon compte</button>
        
        <span style="font-size: 1.1rem; color: #666;">
            Déjà inscrit ? 
            <a href="login.php" style="color: #007bff; text-decoration: none; font-weight: bold;">Se connecter</a>
        </span>
    </div>
    </p>
            </form>
        </article>  

       <aside>
    <h3>Pourquoi s'inscrire ?</h3>
    <p><strong>Personnalisation :</strong> Choisissez via une API de chat votre image de profil unique et amusez-vous avec notre mini-game intégré !</p>
    <p><strong>Sécurité :</strong> Votre vie privée est notre priorité. Nous utilisons un hachage SHA-256 pour sécuriser votre connexion sans stocker votre IP en clair.
    <br><strong style="color: #d32f2f;">Note technique :</strong> Votre adresse IP est récupérée pour limiter les abus (1 compte/IP). <br><br>
            Pour tester l'interface sans créer de compte, utilisez : <br>
            <strong>Identifiant :</strong> admin | <strong>Mot de passe :</strong> admin</p>
    
    <p><strong>Administration :</strong> Les comptes créé sont automatiquement admin et peuvent gérer les news et modifier les résultats du club.</p>
</aside>
    </section>

    <footer>
        IFOSUP &copy; 2026 | 
        <a href="privacy.php" style="color: inherit; text-decoration: underline;">Privacy Policy</a> | 
        <a href="terms.php" style="color: inherit; text-decoration: underline;">Terms Of Use</a>
    </footer>
</div>
</body>
</html>