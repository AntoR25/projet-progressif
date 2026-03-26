<?php
session_start(); // On récupère la session existante
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
<title>Home</title>
<meta charset="utf-8">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="">
<meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="viewport" content="width=device-width, initial-scale=1">
	
<!-- Favicône -->	
<link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="immages/ifosup.png">
<link rel="icon" type="image/png" sizes="16x16" href="immages/ifosup.png">
<link rel="manifest" href="images/favicon/site.webmanifest">
<link rel="mask-icon" href="images/favicon/safari-pinned-tab.svg" color="#5bbad5">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="theme-color" content="#ffffff">


<!-- CSS -->
<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/reset.css" type="text/css" media="screen">
<link rel="stylesheet" type="text/css" href="css/styles.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
 
    <style>
        h3 { text-decoration: underline; margin-bottom: 15px; } 
        aside { padding-top: 5vh; }
        
        /* --- STYLES NEIGE --- */
        #snow-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            pointer-events: none;
            z-index: 9999;
            display: none; /* Caché par défaut */
        }

        .snowflake {
            position: fixed;
            top: -10px;
            background: white;
            border-radius: 50%;
            filter: blur(1px);
            opacity: 0.8;
            animation: fall linear infinite;
        }

        @keyframes fall {
            to { transform: translateY(105vh); }
        }

        /* --- STYLE CLICKER --- */
        #christmas-clicker {
            margin-top: 30px; 
            padding: 20px; 
            background: #fafafa; 
            border: 1px solid #eee; 
            border-radius: 8px; 
            text-align: center; 
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
        }
		#cookie-banner {
        position: fixed;
        bottom: 20px;
        left: 50%;
        transform: translateX(-50%); /* Centre la bannière horizontalement */
        width: 90%;
        max-width: 900px;
        background: rgba(26, 26, 26, 0.95); /* Un peu de transparence c'est plus joli */
        backdrop-filter: blur(10px); /* Flou derrière la bannière */
        color: white;
        padding: 15px 25px; /* Réduit la hauteur */
        display: none; 
        z-index: 9999;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0,0,0,0.5);
        border: 1px solid rgba(255,255,255,0.1);
        
        /* Alignement des éléments sur une seule ligne */
        align-items: center;
        justify-content: space-between;
        flex-direction: row; /* Force l'alignement horizontal */
    }

    .cookie-text {
        flex: 1;
        margin-right: 20px;
        font-size: 0.9rem;
        line-height: 1.4;
    }

    .btn-accept { 
        background: #28a745; 
        color: white; 
        border: none; 
        padding: 10px 20px; 
        border-radius: 8px; 
        cursor: pointer; 
        font-weight: bold;
        white-space: nowrap; /* Empêche le bouton de revenir à la ligne */
        transition: 0.2s;
    }

    .btn-accept:hover { background: #218838; transform: scale(1.05); }

    .btn-info { color: #007bff; text-decoration: none; font-weight: bold; }
    .btn-info:hover { text-decoration: underline; }

	/* 1. On cible l'ID spécifique pour être sûr d'écraser le gris du navigateur */
#btn-stop-snow.btn-winter-stop {
    /* Supprime le style gris par défaut */
    appearance: none;
    -webkit-appearance: none;
    border: none;
    outline: none;

    /* Le Design : Rouge dégradé vif */
    background: linear-gradient(135deg, #ff4757 0%, #ff6b81 100%);
    color: #ffffff !important; /* Force le texte en blanc */
    
    /* Forme et Espacement */
    padding: 15px 30px;
    border-radius: 12px;
    font-family: 'Poppins', sans-serif; /*police actuelle */
    font-weight: 800;
    font-size: 14px;
    text-transform: uppercase;
    letter-spacing: 1px;
    
    /* Alignement icône + texte */
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    
    /* Ombre portée pour décoller du fond */
    box-shadow: 0 10px 20px rgba(255, 71, 87, 0.4);
    cursor: pointer;
    transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

/* 2. Effet au passage de la souris */
#btn-stop-snow.btn-winter-stop:hover {
    transform: translateY(-3px) scale(1.02);
    box-shadow: 0 15px 30px rgba(255, 71, 87, 0.6);
    background: linear-gradient(135deg, #ff6b81 0%, #ff4757 100%);
}

/* 3. L'icône qui tourne */
.icon-sun i {
    animation: spin 4s linear infinite;
    font-size: 18px;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
    </style>
</head>

<body>

<div id="cookie-banner">
    <div class="cookie-text">
        <strong>🍪 Cookies du Club</strong> — 
        Nous utilisons des cookies essentiels pour votre connexion et l'expérience running. 
        <a href="cookies.php" class="btn-info">En savoir plus</a>
    </div>
    <button onclick="acceptCookies()" class="btn-accept">D'accord !</button>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // On vérifie si l'utilisateur a déjà accepté
        if (!localStorage.getItem("cookiesAccepted")) {
            document.getElementById("cookie-banner").style.display = "flex";
        }
    });

    function acceptCookies() {
        // On enregistre le choix dans le navigateur
        localStorage.setItem("cookiesAccepted", "true");
        // On cache le bandeau avec une petite animation
        const banner = document.getElementById("cookie-banner");
        banner.style.transition = "opacity 0.5s";
        banner.style.opacity = "0";
        setTimeout(() => banner.style.display = "none", 500);
    }
</script>
<div id="snow-container"></div>

<div id="wrapper">

    <header>
        <figure id="logo">  
            <img src="immages/empty.png" alt="Logo" width="200" height="200">
            <figcaption><h1>IFOSUP Running Club</h1></figcaption>
        </figure>

        <nav>
            <ul>
                <li><a href="index.php" class="lienactif"> HOME </a></li>
                <li><a href="News.php"> NEWS</a></li>
                <li><a href="Results.php"> RESULTS</a></li>
                <li><a href="Contact.php"> CONTACT</a></li>

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
                        <a href="login.php" class="login-icon">
                             <i class="fa-solid fa-right-to-bracket"></i> 
                        </a>
                    </li>
                <?php endif; ?>
            </ul>   
        </nav>
    </header>

    <section>
        <article id="galerie">  
    <h2>Notre club</h2>
    
    <ul>
        <h3>Gallerie Photos</h3>
        
        <li>
            <figure>
                <img src="immages/school-ifosup.jpg" 
                     width="200" 
                     height="200" 
                     alt="Façade du bâtiment de l'école IFOSUP à Wavre" 
                     title="Notre établissement de formation">
                <figcaption>Ecole de L'IFOSUP</figcaption>
            </figure>
        </li>
        
        <li>
            <figure>
                <img src="immages/ifosuplogo.png" 
                     width="200" 
                     height="200" 
                     alt="Logo officiel de l'IFOSUP Running Club" 
                     title="L'identité de notre club">
                <figcaption>Le Logo de notre CLUB</figcaption>
            </figure>
        </li>
        
        <li>
            <figure>
                <img src="immages/piste.png" 
                     width="200" 
                     height="200" 
                     alt="Vue de la piste d'athlétisme rouge pour les entraînements" 
                     title="Là où nous courons ensemble">
                <figcaption>Notre Piste</figcaption>
            </figure>
        </li>
        
        <li>
            <figure>
                <img src="immages/equip2.png" 
                     width="200" 
                     height="200" 
                     alt="Équipement complet de course à pied prêt pour le départ" 
                     title="Les tenues officielles du club">
                <figcaption>Equipement Ready !</figcaption>
            </figure>
        </li>
        
        <li>
            <figure>
                <img src="immages/equip.png" 
                     width="200" 
                     height="200" 
                     alt="Vue des travaux en cours sur le terrain du club" 
                     title="Amélioration de nos infrastructures">
                <figcaption>Travaux en préparation</figcaption>
            </figure>
        </li>
        
        <li>
            <figure>
                <img src="immages/trophy.png" 
                     width="200" 
                     height="200" 
                     alt="Trophée doré de la première édition de notre compétition de running" 
                     title="Récompense de notre course annuelle">
                <figcaption>Le trophée, Edition 1</figcaption>
            </figure>
        </li>
    </ul>
</article>

     <aside>
    <br>
    <p>
        <span class="coachtype">Welcome !</span> 
        Bienvenue sur notre site de jogging, dédié aux amoureux de la course à pied ! 
        Que vous soyez débutant ou coureur expérimenté, vous trouverez ici toutes les informations et les 
        conseils dont vous avez besoin pour atteindre vos objectifs de course.
        Nous sommes passionnés par la course à pied et nous sommes convaincus que c'est l'un 
        des meilleurs moyens de se maintenir en forme et de se sentir bien dans sa peau.
    </p>
    <p>
        <span class="coachtype">Qui sommes nous ?</span> 
        L'école de l'IFOSUP à Wavre est un établissement d'enseignement supérieur dédié à la formation 
        des professionnels de l'informatique et des nouvelles technologies. En tant que club sportif 
        affilié à l'IFOSUP, notre objectif est de promouvoir l'équilibre entre le travail intellectuel 
        et l'activité physique. La course à pied est une passion que nous partageons et qui nous permet 
        de renforcer notre esprit d'équipe, notre endurance et notre bien-être général.<br><br>

        Notre club accueille des étudiants, des enseignants, des membres du personnel et des amateurs 
        de course à pied de tous niveaux. Que vous soyez un débutant cherchant à améliorer votre 
        condition physique ou un coureur expérimenté visant de nouveaux records, vous trouverez un 
        environnement stimulant et motivant au sein de notre club.<br><br>

        Nous organisons régulièrement des compétitions de course à pied, des entraînements collectifs, 
        des séances de renforcement musculaire et des conférences sur la nutrition et la préparation mentale. 
        Notre équipe d'entraîneurs expérimentés est là pour vous guider et vous aider à atteindre vos objectifs personnels.
    </p>

    <div id="christmas-clicker" 
         alt="Un petit défi, débloquez l'animation ">
        <h4 style="color: #043569; margin-bottom: 15px; font-weight: bold;">Faire tomber la neige</h4>
        
        <button onclick="incrementSnow()" 
                style="background: #007bff; color: white; border: none; border-radius: 50%; width: 50px; height: 50px; font-size: 1.5rem; cursor: pointer; box-shadow: 0 3px 6px rgba(0,0,0,0.2); transition: 0.2s;"
                title="Cliquez 100 fois pour faire tomber la neige sur le site">
            <i class="fa-solid fa-hand-pointer"></i>
        </button>
        
        <p style="margin-top: 15px; font-size: 1rem; color: #555;">
            Compteur : <b><span id="snow-count" style="font-size: 1.2rem; color: #d32f2f;">0</span> / 100</b>
        </p>
    </div>
	
</aside>
    </section>

    <div id="ai-support" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
    <button onclick="toggleChat()" style="background: #007bff; border: none; border-radius: 50%; width: 60px; height: 60px; cursor: pointer; box-shadow: 0 4px 15px rgba(0,0,0,0.3);">
        <i class="fa-solid fa-robot" style="color: white; font-size: 1.5rem;"></i>
    </button>

    <div id="chat-window" style="display: none; position: absolute; bottom: 80px; right: 0; width: 300px; background: white; border-radius: 12px; box-shadow: 0 5px 25px rgba(0,0,0,0.2); border: 1px solid #ddd; overflow: hidden;">
        <div style="background: #007bff; color: white; padding: 15px; font-weight: bold;">
            Gemini Assistant - Running Club
        </div>
        <div id="chat-content" style="height: 250px; padding: 15px; overflow-y: auto; font-size: 0.9rem; color: #555;">
            Bonjour ! Je suis l'IA du club. Comment puis-je vous aider aujourd'hui ?
        </div>
        <div style="padding: 10px; border-top: 1px solid #eee; display: flex;">
            <input type="text" id="user-query" placeholder="Posez votre question..." style="flex: 1; border: 1px solid #ddd; padding: 8px; border-radius: 4px; outline: none;">
            <button onclick="askAI()" style="background: #007bff; color: white; border: none; padding: 0 10px; margin-left: 5px; border-radius: 4px; cursor: pointer;">
                <i class="fa-solid fa-paper-plane"></i>
            </button>
        </div>
		
    </div>
	
</div>

    <footer>
        IFOSUP &copy; 2026 | <a href="privacy.php">Privacy Policy</a> | <a href="terms.php">Terms Of Use</a>
    </footer>

</div> 

<script>
function toggleChat() {
    const chat = document.getElementById('chat-window');
    chat.style.display = chat.style.display === 'none' ? 'block' : 'none';
}

async function askAI() {
    const input = document.getElementById('user-query');
    const content = document.getElementById('chat-content');
    const question = input.value.trim();

    if(question !== "") {
        // 1. Afficher ta question
        content.innerHTML += `<div style="margin-bottom:10px; text-align:right;">
                                <span style="background:#eee; padding:5px 10px; border-radius:10px; display:inline-block;">
                                    <b>Vous :</b> ${question}
                                </span>
                              </div>`;
        
        // 2. Afficher l'état de réflexion
        const loadingId = "loading-" + Date.now();
        content.innerHTML += `<p id="${loadingId}" style="color:#007bff;"><i>L'IA court vers la réponse...</i></p>`;
        input.value = "";
        content.scrollTop = content.scrollHeight; // Scroll vers le bas

        try {
            // 3. Appeler le fichier PHP
            const response = await fetch('ia_handler.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ question: question })
            });
            
            const data = await response.json();
            
            // 4. Remplacer le chargement par la réponse
            document.getElementById(loadingId).remove();
            content.innerHTML += `<p style="margin-bottom:15px; color:#333; line-height:1.4;">
                                    <i class="fa-solid fa-robot" style="color:#007bff;"></i> 
                                    <b>Gemini :</b> ${data.answer}
                                  </p>`;
        } catch (error) {
            document.getElementById(loadingId).innerHTML = "Erreur de connexion avec l'IA.";
        }
        
        content.scrollTop = content.scrollHeight;
    }
}


//CLICKER 
let count = 0;

// Au chargement, on vérifie si on doit relancer la neige
document.addEventListener("DOMContentLoaded", function() {
    if (localStorage.getItem('snow_master') === 'true') {
        startSnow();
    }
});

function incrementSnow() {
    if (localStorage.getItem('snow_master') === 'true') return;
    count++;
    document.getElementById('snow-count').innerText = count;
    
    if (count === 100) {
        localStorage.setItem('snow_master', 'true');
        startSnow();
    }
}

function startSnow() {
    const clicker = document.getElementById('christmas-clicker');
    if (clicker) {
        if (!document.getElementById('btn-stop-snow')) {
            clicker.innerHTML = `
                <div class="stop-container">
                    <h4 style="margin-bottom:15px;">L'hiver est là !</h4>
                    <button id="btn-stop-snow" onclick="stopWinter()" class="btn-winter-stop" disabled style="opacity:0.5; cursor:not-allowed;">
                        <span class="icon-sun"><i class="fa-solid fa-sun" id="sun-loader"></i></span>
                        <span class="text-stop" id="text-timer">PATIENTEZ... (2s)</span>
                    </button>
                </div>
            `;

            // --- LA PROTECTION (2 secondes) ---
            const btn = document.getElementById('btn-stop-snow');
            const text = document.getElementById('text-timer');
            
            setTimeout(() => {
                btn.disabled = false;
                btn.style.opacity = "1";
                btn.style.cursor = "pointer";
                text.innerText = "DÉGELER LE SITE";
                
                // Petit effet visuel : l'icône se met à tourner quand c'est prêt
                document.querySelector('.icon-sun').style.animation = "spin-sun 4s linear infinite";
            }, 2000); 
        }
    }

    // Lancement de la neige
    const container = document.getElementById('snow-container');
    if (container) {
        container.style.display = 'block';
        container.innerHTML = ''; 
        for (let i = 0; i < 100; i++) { createSnowflake(); }
    }
}

function stopWinter() {
    localStorage.removeItem('snow_master');
    location.reload();
}

function createSnowflake() {
    const container = document.getElementById('snow-container');
    if (!container) return;
    
    const snowflake = document.createElement('div');
    snowflake.className = 'snowflake';
    const size = (Math.random() * 8 + 3) + 'px';
    snowflake.style.width = size;
    snowflake.style.height = size;
    snowflake.style.left = Math.random() * 100 + 'vw';
    snowflake.style.animationDuration = (Math.random() * 3 + 2) + 's';
    snowflake.style.animationDelay = Math.random() * 5 + 's';
    container.appendChild(snowflake);
}
// On attend que le DOM soit chargé pour lier l'événement
document.addEventListener("DOMContentLoaded", function() {
    const input = document.getElementById('user-query');
    
    if (input) {
        input.addEventListener("keypress", function(event) {
            // Si la touche appuyée est "Enter"
            if (event.key === "Enter") {
                // Empêche le comportement par défaut (comme recharger la page)
                event.preventDefault(); 
                // Appelle ta fonction d'envoi
                askAI();
            }
        });
    }
});
</script>

</body>
</html>