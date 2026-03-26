<?php
session_start();

// SÉCURITÉ : Vérification de la connexion 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("connexion.php"); 

// API Robohash pour l'avatar dynamique (Option B du projet) [cite: 52]
$avatar_seed = $_SESSION['user_avatar_seed'] ?? $_SESSION['username'];
$username = htmlspecialchars($_SESSION['username']);
$avatar_url = "https://robohash.org/" . urlencode($avatar_seed) . "?set=set4&size=200x200";

// Leaderboard : Meilleur score unique par utilisateur (Partie 04 : BDD) [cite: 9]
$query = $connexion->query("SELECT username, MAX(score) as max_score FROM leaderboard GROUP BY username ORDER BY max_score DESC LIMIT 5");
$top_scores = $query->fetchAll(PDO::FETCH_OBJ);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Arena Elite - IFOSUP Running Club</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/reset.css" type="text/css">
    <link rel="stylesheet" href="css/styles.css" type="text/css">
    
    <style>
        /* --- CORRECTIF DE MISE EN PAGE GLOBAL --- */
        * { box-sizing: border-box; } /* Crucial pour le calcul des largeurs */
        
        body { background-color: #f8f9fa; color: #333; font-family: sans-serif; }
        
        .game-main-wrapper { 
            max-width: 1200px; 
            margin: 30px auto; 
            display: flex; /* Utilisation de Flexbox pour plus de flexibilité */
            gap: 20px; 
            padding: 0 15px; 
        }

        .panel { 
            background: #fff; 
            border: 1px solid #e0e0e0; 
            border-radius: 15px; 
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }

        /* SIDEBAR LEADERBOARD (Correction Débordement) */
        aside.panel { 
            width: 300px; 
            min-width: 300px; 
            flex-shrink: 0; 
            overflow: hidden; /* Coupe tout ce qui dépasse par erreur */
        }

        .leaderboard-title { 
            color: #007bff; 
            border-bottom: 3px solid #f0f0f0; 
            padding-bottom: 15px; 
            margin-bottom: 20px; 
            font-size: 1.2em;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .leaderboard-title span {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .score-row { 
            display: flex;
            justify-content: space-between;
            align-items: center; 
            padding: 12px 5px; 
            border-bottom: 1px solid #f1f1f1; 
            font-size: 1em;
            width: 100%;
        }

        .score-rank { font-weight: bold; color: #007bff; flex-shrink: 0; margin-right: 10px; }
        .score-name { 
            color: #555; 
            font-weight: 500;
            flex-grow: 1; 
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            margin-right: 10px;
        }
        .score-val { font-weight: bold; color: #333; flex-shrink: 0; }

        /* ARENA DE JEU */
        main.panel.arena { 
            flex-grow: 1; 
            min-width: 0; /* Empêche le débordement en Flexbox */
            position: relative; 
            text-align: center; 
            min-height: 700px; 
            display: flex; 
            flex-direction: column; 
            justify-content: space-between; 
        }

        .timer-big { font-family: 'Pacifico', cursive; font-size: 3.5em; color: #007bff; margin-bottom: 10px; }

        .timing-container { position: relative; height: 350px; display: flex; justify-content: center; align-items: center; }
        .target-circle { position: absolute; width: 200px; height: 200px; border: 4px solid #007bff; border-radius: 50%; opacity: 0.1; }
        #movingRing { position: absolute; width: 200px; height: 200px; border: 6px solid #eeff00; border-radius: 50%; transform: scale(2.5); pointer-events: none; }

        .pet-img { width: 160px; z-index: 5; }

        /* STATS & BARRES */
        .stats-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px; }
        .progress-bar { background: #eee; height: 16px; border-radius: 8px; overflow: hidden; border: 1px solid #ddd; }
        .progress-fill { height: 100%; width: 100%; transition: width 0.2s linear; }

        .actions-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; }
        .btn-action { background: #fff; border: 2px solid #007bff; color: #007bff; padding: 15px; border-radius: 10px; font-weight: bold; cursor: pointer; transition: 0.2s; }
        .btn-action:hover:not(:disabled) { background: #007bff; color: #fff; }

        #feedback { position: absolute; width: 100%; top: 15%; font-weight: bold; font-size: 3em; z-index: 10; display: none; }
        .overlay-death { position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(255,255,255,0.98); z-index: 100; display: none; flex-direction: column; justify-content: center; align-items: center; border-radius: 15px; }
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
                <li><a href="admin_panel.php"> MON COMPTE </a></li>
                <li><a href="game.php" class="lienactif"> GAME </a></li>
            </ul>   
        </nav>   
    </header>

    <div class="game-main-wrapper">
        <aside class="panel">
            <h3 class="leaderboard-title">
                <i class="fa-solid fa-crown"></i> 
                <span>RECORDS</span>
            </h3>
            
            <?php if(empty($top_scores)): ?>
                <p style="text-align:center; color:#999; padding:20px;">Aucun record.</p>
            <?php else: ?>
                <?php foreach($top_scores as $index => $s): ?>
                    <div class="score-row">
                        <span class="score-rank">#<?= $index+1 ?></span>
                        <span class="score-name" title="<?= htmlspecialchars($s->username) ?>"><?= htmlspecialchars($s->username) ?></span>
                        <span class="score-val"><?= $s->max_score ?>s</span>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </aside>

        <main class="panel arena">
            <div id="deathBox" class="overlay-death">
                <h2 style="color: #ff4757; font-size: 3.5em; font-weight: bold; text-transform: uppercase; margin-bottom: 10px;">
                GAME OVER
                </h2>
                <h2 style="margin: 20px 0;">Survie : <span id="finalScore">0</span>s</h2>
                <button onclick="location.reload()" class="btn-action" style="width: 200px;">REESSAYER</button>
            </div>

            <div class="timer-big" id="timerDisplay">0s</div>
            <div id="feedback">PERFECT!</div>

            <div class="timing-container">
                <div class="target-circle"></div>
                <div id="movingRing"></div>
                <img src="<?= $avatar_url ?>" id="pet" class="pet-img">
            </div>

            <div class="stats-grid">
                <?php 
                $bars = [
                    'hunger' => ['🍖 FAIM', '#2ed573'], 
                    'happiness' => ['❤️ JOIE', '#ff4757'], 
                    'energy' => ['⚡ ENERGIE', '#ffa502'], 
                    'hygiene' => ['✨ PROPRETÉ', '#1e90ff']
                ];
                foreach($bars as $id => $data): ?>
                <div>
                    <span style="font-size:0.7em; font-weight:bold; color:#777; display:block; text-align:left; margin-bottom:5px;"><?= $data[0] ?></span>
                    <div class="progress-bar"><div id="bar-<?= $id ?>" class="progress-fill" style="background:<?= $data[1] ?>;"></div></div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="actions-grid">
                <button class="btn-action" onclick="game.play('hunger', 25)">NOURRIR</button>
                <button class="btn-action" onclick="game.play('happiness', 20)">JOUER</button>
                <button class="btn-action" onclick="game.play('energy', 30)">DORMIR</button>
                <button class="btn-action" onclick="game.play('hygiene', 30)">LAVER</button>
            </div>
        </main>
    </div>
</div>

<script>
/**
 * [cite_start]CLASSE JAVASCRIPT : ArenaGame 
 * Gère le rythme constant du cercle et la dégradation accélérée des stats.
 */
class ArenaGame {
    constructor() {
        this.stats = { hunger: 100, happiness: 100, energy: 100, hygiene: 100 };
        this.score = 0;
        this.difficulty = 1.0; 
        this.ringScale = 2.5;
        this.alive = true;
        this.init();
    }

    init() {
        // Moteur du cercle (Vitesse fixe)
        setInterval(() => {
            if(!this.alive) return;
            this.ringScale -= 0.035; 
            if (this.ringScale <= 0.4) this.ringScale = 2.5;
            document.getElementById('movingRing').style.transform = `scale(${this.ringScale})`;
        }, 20);

        // Moteur de survie (Difficulté croissante)
        setInterval(() => {
            if(!this.alive) return;
            this.score++;
            this.difficulty += 0.1;
            document.getElementById('timerDisplay').innerText = this.score + 's';
            
            for(let s in this.stats) {
                this.stats[s] -= (1.6 * this.difficulty); 
                document.getElementById(`bar-${s}`).style.width = Math.max(0, this.stats[s]) + "%";
                if(this.stats[s] <= 0) this.gameOver();
            }
        }, 1000);
    }

    play(stat, value) {
        if(!this.alive) return;
        const dist = Math.abs(this.ringScale - 1.0);
        let bonus = 0, msg = "", color = "";

        if (dist < 0.08) { bonus = 2.0; msg = "PERFECT!"; color = "#2ed573"; }
        else if (dist < 0.18) { bonus = 1.2; msg = "VERY GOOD"; color = "#1e90ff"; }
        else if (dist < 0.35) { bonus = 0.8; msg = "GOOD"; color = "#ffa502"; }
        else if (dist < 0.6) { bonus = 0.3; msg = "OK"; color = "#777"; }
        else { bonus = -1.0; msg = "FAIL!"; color = "#ff4757"; }

        this.stats[stat] = Math.min(100, this.stats[stat] + (value * bonus));
        this.showFeedback(msg, color);
        this.ringScale = 2.5; 
    }

    showFeedback(m, c) {
        const f = document.getElementById('feedback');
        f.innerText = m; f.style.color = c; f.style.display = 'block';
        setTimeout(() => f.style.display = 'none', 700);
    }

    gameOver() {
        if(!this.alive) return;
        this.alive = false;
        document.getElementById('deathBox').style.display = 'flex';
        document.getElementById('finalScore').innerText = this.score;
        const d = new FormData(); 
        d.append('score', this.score);
        fetch('save_score.php', { method: 'POST', body: d }); 
    }
}

const game = new ArenaGame();
</script>
</body>
</html>