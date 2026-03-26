<?php
session_start();

// INCLURE LA CONNEXION (C'est l'étape qui manquait !)
include 'connexion.php'; 

// SÉCURITÉ : On vérifie si l'utilisateur est connecté 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// --- GESTION DE L'AVATAR (Désormais $connexion est connu) ---
if (isset($_GET['set_avatar'])) {
    $new_seed = htmlspecialchars($_GET['set_avatar']);
    $user_id = $_SESSION['user_id'];

    $stmt = $connexion->prepare("UPDATE utilisateurs SET avatar_seed = :seed WHERE id = :id");
    $stmt->execute([
        'seed' => $new_seed,
        'id' => $user_id
    ]);

    $_SESSION['user_avatar_seed'] = $new_seed;
    header("Location: admin_panel.php");
    exit();
}

// Récupération de la seed actuelle pour l'affichage
$avatar_seed = $_SESSION['user_avatar_seed'] ?? $_SESSION['username'];
$username = htmlspecialchars($_SESSION['username']);

// Robohash API - Set 4 (Cats) 
$avatar_url = "https://robohash.org/" . urlencode($avatar_seed) . "?set=set4&size=150x150";

// Liste des chats pour la galerie
$cat_options = [
    'Misty', 'Felix', 'Leo', 'Luna', 'Simba', 'Chloe', 'Oliver', 'Bella', 'Smokey', 'Jack', 
    'Loki', 'Tigger', 'Jasper', 'Daisy', 'Oscar', 'Willow', 'Lucky', 'Sooty', 'Casper', 'Cookie', 
    'Ginger', 'Tiger', 'Sam', 'Sasha', 'Ruby', 'Molly', 'Coco', 'Buster', 'Pepper', 'Gismo', 
    'Mittens', 'Ziggy', 'Zoe', 'Merlin', 'Nala', 'Alfie', 'George', 'Max', 'Bonnie', 'Patch', 
    'Rosie', 'Teddy', 'Sebastian', 'Princess', 'Kiki', 'Toby', 'Minerva', 'Sylvester', 'Marley', 'Skye',
    'Shadow', 'Midnight', 'Snowball', 'Pumpkin', 'Boots', 'Salem', 'Binx', 'Otis', 'Peanut', 'Bubba',
    'Cleo', 'Rex', 'Milo', 'Blue', 'Frankie', 'Archie', 'Hunter', 'Moose', 'Bear', 'Zeus',
    'Apollo', 'Thor', 'Lulu', 'Pip', 'Sunny'
];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Admin Panel - IFOSUP Running Club</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link rel="icon" type="image/png" href="immages/ifosup.png">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/reset.css" type="text/css">
    <link rel="stylesheet" type="text/css" href="css/styles.css">
    
    <style>
        body { background-color: #f8f9fa; color: #333; font-family: sans-serif; position: relative; }

        /* --- DESIGN RADIO COMPACT (PILULE) --- */
        .radio-widget {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(8px); /* Effet de flou moderne */
            padding: 8px 18px;
            border-radius: 50px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border: 1px solid rgba(255,255,255,0.3);
            width: auto;
            transition: all 0.3s ease;
        }

        .radio-widget:hover { transform: translateY(-3px); box-shadow: 0 12px 30px rgba(0,0,0,0.15); }

        .radio-label {
            font-size: 0.65rem;
            font-weight: 800;
            color: #007bff;
            letter-spacing: 1px;
            text-transform: uppercase;
        }

        /* --- CORRECTIF BOUTONS VISIBLES --- */
.radio-btn {
    background: #eeeeee; /* Gris clair pour bien voir le bouton */
    border: none; 
    width: 32px; 
    height: 32px;
    border-radius: 50%; 
    cursor: pointer; 
    display: flex; 
    align-items: center;
    justify-content: center; 
    transition: 0.2s;
    color: #000000 !important; /* FORCE LA COULEUR NOIRE */
    font-size: 14px;
}

.radio-btn i {
    display: inline-block;
    color: #000 !important; /* Force l'icône en noir */
}

.radio-btn:hover { 
    background: #007bff; 
    color: #ffffff !important; 
}

.radio-btn:hover i {
    color: #fff !important;
}

        /* Animation des ondes sonores */
        .wave-container { display: none; align-items: flex-end; gap: 2px; height: 10px; margin-right: 5px; }
        .wave { width: 2px; height: 4px; background: #2ed573; border-radius: 2px; animation: wave-anim 1s infinite; }
        .wave:nth-child(2) { animation-delay: 0.2s; }
        .wave:nth-child(3) { animation-delay: 0.4s; }
        @keyframes wave-anim { 0%, 100% { height: 4px; } 50% { height: 10px; } }

        /* --- TON DESIGN ORIGINAL --- */
        .admin-wrapper {
            max-width: 900px;
            margin: 40px auto;
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 20px;
        }
        .profile-card, .menu-card {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            border: 1px solid #e0e0e0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            text-align: center;
        }
        .profile-img {
            width: 120px; height: 120px; border-radius: 50%;
            background: #f0f0f0; border: 3px solid #007bff;
            margin-bottom: 15px; cursor: pointer; transition: transform 0.2s;
        }
        .profile-img:hover { transform: scale(1.05); }
        
        .avatar-gallery {
            display: none; grid-template-columns: repeat(auto-fill, minmax(60px, 1fr));
            gap: 10px; margin-top: 20px; padding: 15px; background: #fdfdfd;
            border: 1px dashed #ccc; border-radius: 8px; max-height: 200px; overflow-y: auto;
        }
        .avatar-option { width: 100%; border-radius: 5px; cursor: pointer; transition: 0.2s; }
        .avatar-option:hover { transform: scale(1.1); background: #e0f0ff; }

        .admin-nav-btns { display: flex; flex-direction: column; gap: 15px; margin-top: 20px; }
        .btn { display: block; padding: 15px; border-radius: 8px; text-decoration: none; font-weight: bold; text-transform: uppercase; font-size: 0.85em; transition: 0.3s; }
        .btn-blue { background: #007bff; color: white; }
        .btn-blue:hover { background: #0056b3; box-shadow: 0 4px 12px rgba(0,123,255,0.3); }
        .btn-dark { background: #333; color: white; }
        .btn-dark:hover { background: #000; }
        
        .btn-logout { display: inline-block; padding: 10px 20px; background: #fff5f5; color: #c53030; border: 1px solid #feb2b2; margin-top: 20px; text-decoration: none; border-radius: 5px; font-size: 0.8em; font-weight: bold; }
        h3 { margin-bottom: 20px; color: #555; border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; }
    </style>
</head>

<body>

<aside class="radio-widget">
    <div class="wave-container" id="radioWaves">
        <div class="wave"></div><div class="wave"></div><div class="wave"></div>
    </div>
    
    <span class="radio-label">Radio-Cat</span>

    <div style="display: flex; gap: 6px;">
        <button class="radio-btn" id="playBtn" title="Lecture/Pause"><i class="fa-solid fa-play"></i></button>
        <button class="radio-btn" id="stopBtn" title="Stop"><i class="fa-solid fa-stop"></i></button>
    </div>
    
    <audio id="catMusic" loop>
        <source src="https://www.soundhelix.com/examples/mp3/SoundHelix-Song-2.mp3" type="audio/mpeg">
    </audio>
</aside>

<div id="wrapper">
    <header style="text-align: center; padding-top: 20px;">
        <figure id="logo">
            <img src="immages/empty.png" alt="" width="200" height="200">
            <figcaption><h1>|=Welcome to the panel=|</h1></figcaption>
        </figure>
    </header>

    <div class="admin-wrapper">
    <div class="profile-card">
        <img src="<?php echo $avatar_url; ?>" alt="Avatar" class="profile-img" id="profilePic" title="Changer mon chat">
        <div style="font-size: 1.4em; font-weight: bold;"><?php echo $username; ?></div>
        <p style="color: #888; font-size: 0.75em; margin-top: 5px;">Administrateur Club</p>

        <div id="avatarGallery" class="avatar-gallery">
            <?php foreach ($cat_options as $cat): ?>
                <a href="admin_panel.php?set_avatar=<?php echo $cat; ?>">
                    <img src="https://robohash.org/<?php echo $cat; ?>?set=set4&size=60x60" class="avatar-option" title="<?php echo $cat; ?>">
                </a>
            <?php endforeach; ?>
        </div>

            <hr style="margin: 20px 0; border: 0; border-top: 1px solid #eee;">
            <a href="logout.php" class="btn-logout"><i class="fa-solid fa-power-off"></i> DÉCONNEXION</a>
        <div style="margin-top: 20px; padding-top: 20px;">
    <button onclick="triggerNuke()" id="nuke-btn" style="background: #ff0000; color: white; border: none; padding: 10px 20px; border-radius: 5px; cursor: pointer; font-weight: bold; width: 100%;">
        <i class="fa-solid fa-radiation"></i> DÉTRUIRE LE SITE (NUKE)
    </button>
</div>

<div id="nuke-overlay" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:black; color:red; z-index:10000; text-align:center; padding-top:10vh; font-family: 'Courier New', Courier, monospace;">
    <h1 style="font-size: 5rem; margin-bottom: 20px;">WARNING</h1>
    <h2 id="nuke-timer" style="font-size: 8rem;">10</h2>
    <p style="font-size: 2rem;">AUTO-DESTRUCTION SÉQUENCE INITIÉE</p>
    <div id="nuke-flash" style="position:absolute; top:0; left:0; width:100%; height:100%; background:white; opacity:0; pointer-events:none;"></div>
</div>

<script>
function triggerNuke() {
    if (confirm("ATTENTION : Explosion imminente. Le site sera indisponible 3 min. Continuer ?")) {
        const overlay = document.getElementById('nuke-overlay');
        const timerDisplay = document.getElementById('nuke-timer');
        const flash = document.getElementById('nuke-flash');
        
        overlay.style.display = 'block';
        let timeLeft = 10;
        
        const countdown = setInterval(async () => {
            timeLeft--;
            timerDisplay.innerText = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(countdown);
                
                // 1. Appel au serveur pour activer le nuke
                try {
                    const response = await fetch('ia_handler.php?action=nuke');
                    const data = await response.json();
                    
                    if (data.status === 'nuke_initiated') {
                        // 2. Effet visuel final
                        flash.style.transition = "opacity 0.2s";
                        flash.style.opacity = "1";
                        
                        // 3. Redirection forcée après le flash
                        setTimeout(() => {
                            window.location.href = "404.php";
                        }, 600);
                    }
                } catch (e) {
                    window.location.href = "error.php"; // Secours
                }
            }
        }, 1000);
    }
}
</script>
        </div>
        

        <div class="menu-card">
            <h3>GESTION ADMINISTRATIVE</h3>
            <div class="admin-nav-btns">
                <a href="adminresults.php" class="btn btn-blue">Modifier les Résultats</a>
                <a href="adminnews.php" class="btn btn-blue">Gérer les News</a>
                <a href="game.php" class="btn btn-blue">Play a game (Bonus)</a>
                <a href="index.php" class="btn btn-dark">Retour au site public</a>
            </div>
            
            <div style="margin-top: 30px; padding: 15px; background: #fcfcfc; border-radius: 8px; border: 1px solid #f0f0f0;">
                <p style="font-size: 0.8em; color: #999;">
                    <i class="fa-solid fa-circle-info"></i> Cliquez sur votre avatar à gauche pour changer de mascotte.
                </p>
            </div>
        </div>
    </div>

    <footer style="text-align:center; padding: 20px; color: #777;">
        IFOSUP &copy; 2026 | Espace Administration
    </footer>
</div>

<script>
/**
 * CLASSE RadioManager
 * Gère le widget audio compact en haut à droite
 */
class RadioManager {
    constructor() {
        this.audio = document.getElementById('catMusic');
        this.playBtn = document.getElementById('playBtn');
        this.stopBtn = document.getElementById('stopBtn');
        this.waves = document.getElementById('radioWaves');
        this.isPlaying = false;
        this.init();
    }

    init() {
        this.playBtn.addEventListener('click', () => this.toggle());
        this.stopBtn.addEventListener('click', () => this.stop());
    }

    toggle() {
        if (!this.isPlaying) {
            this.audio.play();
            this.playBtn.innerHTML = '<i class="fa-solid fa-pause"></i>';
            this.waves.style.display = 'flex';
        } else {
            this.audio.pause();
            this.playBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
            this.waves.style.display = 'none';
        }
        this.isPlaying = !this.isPlaying;
    }

    stop() {
        this.audio.pause();
        this.audio.currentTime = 0;
        this.isPlaying = false;
        this.playBtn.innerHTML = '<i class="fa-solid fa-play"></i>';
        this.waves.style.display = 'none';
    }
}

/**
 * CLASSE GalleryManager
 * Gère l'affichage de la galerie de chats
 */
class GalleryManager {
    constructor(triggerId, targetId) {
        this.trigger = document.getElementById(triggerId);
        this.target = document.getElementById(targetId);
        if (this.trigger && this.target) { this.init(); }
    }
    init() { this.trigger.addEventListener('click', () => this.toggle()); }
    toggle() {
        const isVisible = this.target.style.display === 'grid';
        this.target.style.display = isVisible ? 'none' : 'grid';
    }
}

// Initialisation au chargement
document.addEventListener('DOMContentLoaded', () => {
    new GalleryManager('profilePic', 'avatarGallery');
    new RadioManager();
});
</script>

</body>
</html>