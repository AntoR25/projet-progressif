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

// 1. CONNEXION BDD (Local)
$servername = "localhost";
$dbname = "examen";
$username = "root"; 
$password = ""; 

try {
    $connexion = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// 2. LOGIQUE PHP
$error_msg = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = trim($_POST['username']);
    $pass_input = $_POST['password'];

    if (!empty($user_input) && !empty($pass_input)) {
        // On sélectionne tout (*) pour bien avoir l'avatar_seed
        $stmt = $connexion->prepare("SELECT * FROM utilisateurs WHERE username = :u");
        $stmt->execute(['u' => $user_input]);
        $user = $stmt->fetch();

        if ($user && password_verify($pass_input, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // --- AJOUT ICI : On stocke le chat dans la session ---
            // Si la colonne est vide dans la BDD, on met le pseudo par défaut
            $_SESSION['user_avatar_seed'] = !empty($user['avatar_seed']) ? $user['avatar_seed'] : $user['username'];
            
            header("Location: admin_panel.php"); 
            exit();
        } else {
            $error_msg = "Identifiants incorrects.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion | Running Club</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        /* Design Minimaliste & Plein Écran */
        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative; /* Pour le positionnement du bouton home */
        }

        /* Style du bouton Maison */
        .home-link {
            position: absolute;
            top: 30px;
            left: 30px;
            background: white;
            width: 50px;
            height: 50px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #1a1a1a;
            text-decoration: none;
            font-size: 1.2rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s ease;
            border: 1px solid #eee;
        }

        .home-link:hover {
            transform: scale(1.1);
            color: #007bff;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
        }

        .login-card {
            background: white;
            width: 100%;
            max-width: 400px;
            padding: 50px;
            border-radius: 20px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            text-align: center;
        }

        .logo-text {
            font-size: 1.5rem;
            font-weight: 800;
            color: #333;
            margin-bottom: 10px;
            letter-spacing: -1px;
        }

        .subtitle {
            color: #888;
            font-size: 0.9rem;
            margin-bottom: 30px;
        }

        .input-group {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-group label {
            display: block;
            font-size: 0.8rem;
            font-weight: 600;
            color: #666;
            margin-bottom: 8px;
            margin-left: 5px;
        }

        .input-group input {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid #eee;
            background: #fcfcfc;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .input-group input:focus {
            outline: none;
            border-color: #007bff;
            background: #fff;
            box-shadow: 0 0 0 4px rgba(0, 123, 255, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: #1a1a1a;
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s ease, background 0.3s ease;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #000;
            transform: translateY(-2px);
        }

        .error-msg {
            background: #fff5f5;
            color: #c53030;
            padding: 12px;
            border-radius: 10px;
            font-size: 0.85rem;
            margin-bottom: 20px;
            border: 1px solid #feb2b2;
        }

        .links {
            margin-top: 25px;
            font-size: 0.85rem;
            color: #999;
        }

        .links a {
            color: #007bff;
            text-decoration: none;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <a href="index.php" class="home-link" title="Retour à l'accueil">
        <i class="fa-solid fa-house"></i>
    </a>

    <div class="login-card">
        <div class="logo-text">IFOSUP RUNNING</div>
        <p class="subtitle">Connectez-vous à votre espace</p>

        <?php if ($error_msg): ?>
            <div class="error-msg"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="input-group">
                <label>UTILISATEUR</label>
                <input type="text" name="username" required autocomplete="off">
            </div>

            <div class="input-group">
                <label>MOT DE PASSE</label>
                <input type="password" name="password" required>
            </div>

            <button type="submit" class="btn-login">Continuer</button>
        </form>

        <div class="links">
            Nouveau ici ? <a href="register.php">Créer un compte</a>
        </div>
    </div>

</body>
</html>