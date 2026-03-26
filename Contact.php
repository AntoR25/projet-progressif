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

$message_status = ""; // Pour afficher un retour à l'utilisateur

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['valid'])) {
    // 1. Récupération et nettoyage des données
    $nom = htmlspecialchars(trim($_POST['nom']));
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $email_client = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message_contenu = htmlspecialchars(trim($_POST['message']));

    // 2. Configuration de l'email
    $to = "antoine.remacle@ifosup.wavre.be";
    $subject = "Nouveau message de : $nom $prenom (IFOSUP Running Club)";
    
    // Construction du corps du mail
    $body = "Vous avez reçu un nouveau message depuis le formulaire de contact.\n\n";
    $body .= "Nom : $nom\n";
    $body .= "Prénom : $prenom\n";
    $body .= "Email : $email_client\n\n";
    $body .= "Message :\n$message_contenu\n";

    // En-têtes pour que le mail soit bien interprété et permette de répondre
    $headers = "From: webmaster@ifosup-running.be" . "\r\n"; // Email fictif de ton serveur
    $headers .= "Reply-To: $email_client" . "\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();

    // 3. Envoi du mail
    if (filter_var($email_client, FILTER_VALIDATE_EMAIL)) {
        if (mail($to, $subject, $body, $headers)) {
            $message_status = "<p class='success'>Votre message a bien été envoyé !</p>";
        } else {
            $message_status = "<p class='error'>Une erreur est survenue lors de l'envoi.</p>";
        }
    } else {
        $message_status = "<p class='error'>Adresse email invalide.</p>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Contact - IFOSUP Running Club</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png" href="immages/ifosup.png">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/reset.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">

    <style>
        /* Styles spécifiques pour le formulaire */
        form fieldset {
            border: 1px solid #ddd;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }
        form legend {
            padding: 0 10px;
            font-weight: bold;
            color: #007bff;
        }
        form p { margin-bottom: 15px; }
        label { display: inline-block; width: 100px; }
        input[type="text"], input[type="email"], textarea {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            max-width: 300px;
            box-sizing: border-box;
        }
        textarea { 
    width: 100%; 
    max-width: 100%; /* Empêche de déborder du conteneur */
    height: 120px; 
    resize: none;   /* <--- C'est cette ligne qui bloque l'étirement */
    padding: 10px;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-sizing: border-box; /* Important pour que le padding ne casse pas la largeur */
}
        
        #valid {
            background: #007bff;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
            transition: 0.3s;
        }
        #valid:hover { background: #0056b3; }
        
        /* Styles pour les messages de retour */
        .success { color: #155724; background-color: #d4edda; padding: 10px; border-radius: 5px; border: 1px solid #c3e6cb; margin-bottom: 20px; }
        .error { color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 5px; border: 1px solid #f5c6cb; margin-bottom: 20px; }

        aside iframe {
            max-width: 100%;
            border-radius: 8px;
        }
    </style>
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
                <li><a href="News.php"> NEWS</a></li>
                <li><a href="Results.php"> RESULTS</a></li>
                <li><a href="Contact.php" class="lienactif"> CONTACT</a></li>

                <?php if(isset($_SESSION['username'])): ?>
                    <li class="nav-auth">
                        <a href="admin_panel.php" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
                            <span>MY ACCOUNT</span>
                            <?php $nav_seed = $_SESSION['user_avatar_seed'] ?? $_SESSION['username']; ?>
                            <img src="https://robohash.org/<?php echo urlencode($nav_seed); ?>?set=set4&size=40x40" 
                                 alt="Profil" style="border-radius: 50%; border: 1px solid #ccc; background: #eee;">
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

    <section style="padding: 20px; display: flex; flex-wrap: wrap; gap: 40px;">
        <article id="content" style="flex: 1; min-width: 300px;">  
            <h2>Contact us !</h2><br>
            
            <?php echo $message_status; ?>

            <form action="Contact.php" method="post">
                <fieldset>
                    <legend> Vos coordonnées </legend>
                    <p>
                        <label for="nom"> Name :</label>
                        <input type="text" name="nom" id="nom" placeholder="Obligatoire" required autofocus />
                    </p>
                    <p> 
                        <label for="prenom"> Surname : </label>
                        <input type="text" name="prenom" id="prenom" />
                    </p>
                    <p>
                        <label for="email">E-Mail : </label>
                        <input type="email" name="email" id="email" placeholder="Obligatoire" required />
                    </p>
                </fieldset>         
                <fieldset>
                    <legend>Your message</legend>
                    <textarea name="message" id="message" placeholder="Écrivez votre message ici..." required></textarea>
                </fieldset>
                <br>
                <input type="submit" name="valid" id="valid" value="Envoyer le message" />
            </form>
        </article>  

        <aside style="flex: 1; min-width: 300px; padding:0;">
    <h2>Maps</h2><br>
    <iframe 
        width="100%" 
        height="300" 
        frameborder="0" 
        scrolling="no" 
        marginheight="0" 
        marginwidth="0" 
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d5052.254962720327!2d4.605189176428755!3d50.71757407164439!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x47c17d741a720cbd%3A0x96bf566252976fb4!2sIfosup%20Wavre%20(Institut%20de%20Formation%20Sup%C3%A9rieure%20de%20la%20Ville%20de%20Wavre)!5e0!3m2!1sfr!2sbe!4v1774311686739!5m2!1sfr!2sbe"></iframe>
    <p><strong>IFOSUP Wavre</strong><br>
    Rue de la Limite 6, 1300 Wavre<br><br>
    Téléphone : 010 22 20 26</p>
</aside>
    </section>
 
    <footer>
        IFOSUP © 2026 | 
        <a href="privacy.php" style="color: inherit; text-decoration: underline;">Privacy Policy</a> | 
        <a href="terms.php" style="color: inherit; text-decoration: underline;">Terms Of Use</a>
    </footer>
</div>
</body>
</html>