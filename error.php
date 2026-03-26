<?php
session_start();

$is_nuke = false;
if (isset($_COOKIE['site_nuke_timer'])) {
    $nuke_until = intval($_COOKIE['site_nuke_timer']);
    if (($nuke_until - time()) > 0) { $is_nuke = true; }
}

$code = $_GET['code'] ?? '404';
$avatar_seed = $_SESSION['user_avatar_seed'] ?? 'default';
$avatar_url = "https://robohash.org/" . urlencode($avatar_seed) . "?set=set4&size=400x400";

$errors = [
    '404' => ['title' => 'PAGE INTROUVABLE', 'msg' => 'Miaou... Le chemin s\'est effacé.', 'color' => '#00a8ff'],
    '403' => ['title' => 'ACCÈS REFUSÉ', 'msg' => 'Grrr ! Zone hautement protégée.', 'color' => '#ff4757'],
    '500' => ['title' => 'ERREUR INTERNE', 'msg' => 'Le serveur a glissé sur une croquette.', 'color' => '#ffa502']
];
$current_error = $errors[$code] ?? $errors['404'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?php echo $is_nuke ? 'SYSTEM FAILURE' : "Erreur $code"; ?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/reset.css" type="text/css">
    <style>
        body { 
            margin: 0; padding: 0; height: 100vh; 
            display: flex; align-items: center; justify-content: center;
            background: #050505; font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: white; overflow: hidden;
        }

        /* Effet de lignes de vieux moniteur */
        body::after {
            content: ""; position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            background: linear-gradient(rgba(18, 16, 16, 0) 50%, rgba(0, 0, 0, 0.25) 50%), 
                        linear-gradient(90deg, rgba(255, 0, 0, 0.03), rgba(0, 255, 0, 0.01), rgba(0, 0, 255, 0.03));
            background-size: 100% 4px, 3px 100%; pointer-events: none; z-index: 10;
        }

        .main-container {
            position: relative; z-index: 5; text-align: center;
            padding: 60px; width: 100%; max-width: 800px;
        }

        /* --- STYLE NUKE --- */
        .nuke-title {
            font-size: 5vw; font-weight: 900; color: #ff3e3e; margin: 0;
            text-shadow: 0 0 20px rgba(255, 62, 62, 0.6);
            animation: glitch 1s infinite alternate;
        }

        .avatar-frame {
            position: relative; display: inline-block; margin: 40px 0;
            border: 2px solid rgba(255, 255, 255, 0.1); padding: 20px;
            background: rgba(255, 255, 255, 0.02); border-radius: 20px;
        }

        .avatar-img { width: 280px; height: auto; display: block; border-radius: 10px; }
        
        .nuke-mode .avatar-img { 
            filter: grayscale(1) sepia(0.3) brightness(0.7); 
            animation: pulse 2s infinite ease-in-out;
        }

        .tear { 
            position: absolute; font-size: 30px; opacity: 0; 
            animation: fall 2s infinite cubic-bezier(0.55, 0.085, 0.68, 0.53); 
        }

        @keyframes fall {
            0% { transform: translateY(0); opacity: 0; }
            30% { opacity: 1; }
            100% { transform: translateY(100px); opacity: 0; }
        }

        @keyframes glitch {
            0% { transform: skew(0deg); }
            20% { transform: skew(-2deg); color: #fff; }
            40% { transform: skew(2deg); }
            100% { transform: skew(0deg); }
        }

        @keyframes pulse { 0% { opacity: 0.6; } 50% { opacity: 1; } 100% { opacity: 0.6; } }

        /* Timer & Lyrics */
        #lyrics { font-size: 1.8em; color: #666; font-style: italic; min-height: 50px; margin-bottom: 30px; }
        .timer-display { font-size: 4em; font-weight: 100; letter-spacing: 10px; color: #fff; }

        /* Style Classique */
        .standard-title { font-size: 4em; color: <?php echo $current_error['color']; ?>; }
        .standard-msg { font-size: 1.5em; opacity: 0.7; margin: 20px 0; }
        .btn-back {
            display: inline-block; padding: 15px 40px; background: transparent;
            border: 2px solid white; color: white; text-decoration: none;
            font-weight: bold; border-radius: 5px; transition: 0.3s;
        }
        .btn-back:hover { background: white; color: black; }
    </style>
</head>
<body class="<?php echo $is_nuke ? 'nuke-mode' : ''; ?>">

    <div class="main-container">
        <?php if($is_nuke): ?>
            <audio id="sad-song" src="musique/miaw miaw miaw song sad (lyrics video visual).mp3" loop></audio>
            
            <h1 class="nuke-title">SYSTEM OFFLINE</h1>
            
            <div class="avatar-frame">
                <img src="<?php echo $avatar_url; ?>" class="avatar-img">
                <span class="tear" style="left: 30%; top: 40%;">💧</span>
                <span class="tear" style="right: 30%; top: 40%; animation-delay: 1s;">💧</span>
            </div>

            <div id="lyrics">clic sur la page pour lancer la musique 😿 </div>
            <div class="timer-display" id="nuke-timer">03:00</div>
            
        <?php else: ?>
            <h1 class="standard-title"><?php echo $code; ?></h1>
            <div class="avatar-frame" style="border-color: <?php echo $current_error['color']; ?>;">
                <img src="<?php echo $avatar_url; ?>" class="avatar-img">
            </div>
            <h2><?php echo $current_error['title']; ?></h2>
            <p class="standard-msg"><?php echo $current_error['msg']; ?></p>
            <a href="index.php" class="btn-back">RETOUR</a>
        <?php endif; ?>
    </div>

<script>
<?php if($is_nuke): ?>
    const song = document.getElementById('sad-song');
    song.volume = 0.05; // Volume extrêmement bas (5%)

    const lines = [
        "Miaw miaw miaw miaw...",
        "Tout est cassé...",
        "On se reverra bientôt ?",
        "Je me sens tout seul...",
        "Le serveur est vide...",
        "Reviens vite..."
    ];
    let idx = 0;
    setInterval(() => {
        const d = document.getElementById('lyrics');
        d.style.opacity = 0;
        setTimeout(() => {
            d.innerText = lines[idx];
            d.style.opacity = 1;
            idx = (idx + 1) % lines.length;
        }, 500);
    }, 4000);

    const until = parseInt(document.cookie.split('; ').find(r => r.startsWith('site_nuke_timer=')).split('=')[1]);

    function tick() {
    const now = Math.floor(Date.now() / 1000);
    const diff = until - now;

    if (diff <= 0) {
        // Fin du nuke
        document.body.innerHTML = "<div style='color:white; text-align:center; margin-top:40vh; font-family:sans-serif;'><h1>RESTAURATION TERMINÉE</h1><p>Redirection vers l'accueil...</p></div>";
        setTimeout(() => { window.location.href = "index.php"; }, 2000);
    } else {
        // Calcul des minutes et secondes
        const minutes = Math.floor(diff / 60);
        const seconds = diff % 60;
        
        // Formatage 00:00
        const displayM = minutes < 10 ? "0" + minutes : minutes;
        const displayS = seconds < 10 ? "0" + seconds : seconds;
        
        document.getElementById('nuke-timer').innerText = displayM + ":" + displayS;
    }
}
    setInterval(tick, 1000);
    tick();

    window.addEventListener('click', () => { song.play(); }, { once: true });
<?php endif; ?>
</script>

</body>
</html>