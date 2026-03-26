<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cookies - IFOSUP Running Club</title>
    <link rel="stylesheet" href="css/styles.css">
    
    <style>
        /* Sécurité au cas où le CSS externe ne charge pas */
        body { 
            margin: 0; 
            padding: 0; 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background-color: #f4f7f6; /* Un gris très léger pour éviter le blanc pur */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .cookie-card {
            background: white;
            max-width: 600px;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            border-top: 5px solid #007bff;
        }

        h1 { color: #333; font-size: 1.8rem; margin-bottom: 20px; }
        h3 { color: #007bff; margin-top: 25px; font-size: 1.1rem; }
        p, li { color: #555; line-height: 1.6; font-size: 0.95rem; }
        ul { padding-left: 20px; }

        .btn-return {
            display: inline-block;
            margin-top: 30px;
            padding: 10px 25px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 50px;
            font-weight: bold;
            transition: 0.3s;
        }
        .btn-return:hover { background: #0056b3; transform: scale(1.05); }
    </style>
</head>
<body>

    <div class="cookie-card">
        <h1>Politique des Cookies 🍪</h1>
        
        <p>Pour que le <strong>IFOSUP Running Club</strong> fonctionne correctement, nous utilisons quelques cookies essentiels.</p>

        <h3>🔹 Cookies de Session</h3>
        <p>Ils servent à vous reconnaître quand vous vous connectez à votre espace administration. Sans eux, vous devriez vous reconnecter à chaque clic.</p>

        <h3>🔹 Cookies de Sécurité (Nuke)</h3>
        <p>Nous utilisons un cookie pour mémoriser le temps restant lors de la mise hors ligne du site (le fameux mode Nuke de 3 minutes).</p>

        <h3>🔹 Consentement</h3>
        <p>Ce cookie sert simplement à ne plus vous afficher la bannière une fois que vous avez cliqué sur "D'accord".</p>

        <p style="margin-top:20px; font-style: italic; color: #888;">
            Aucune donnée n'est collectée à des fins publicitaires.
        </p>

    <div style="margin-top: 50px; text-align: center; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif !important;">
    
    <a href="index.php" class="btn-design-return" style="
        display: inline-flex !important; 
        align-items: center !important; 
        justify-content: center !important; 
        padding: 10px 22px !important; 
        background-color: #ffffff !important; /* Fond blanc minimaliste */
        color: #007bff !important; /* Texte bleu */
        border: 1px solid #d1d1d1 !important; /* Bordure fine grise */
        border-radius: 50px !important; 
        text-decoration: none !important; 
        font-size: 14px !important; 
        font-weight: 500 !important; 
        letter-spacing: -0.2px !important;
        transition: all 0.2s ease-in-out !important; /* Animation fluide */
        box-shadow: 0 2px 5px rgba(0,0,0,0.03) !important; /* Ombre très légère */
    "
    onmouseover="this.style.backgroundColor='#f5faff'; this.style.borderColor='#a1cfff'; this.style.transform='translateY(-2px)';"
    onmouseout="this.style.backgroundColor='#ffffff'; this.style.borderColor='#d1d1d1'; this.style.transform='translateY(0)';"
    >
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="margin-right: 8px; display: inline-block !important; vertical-align: middle !important;">
            <line x1="19" y1="12" x2="5" y2="12"></line>
            <polyline points="12 19 5 12 12 5"></polyline>
        </svg>
        
        <span style="color: #007bff !important; display: inline !important; visibility: visible !important; font-size: 14px !important; font-weight: 600 !important; opacity: 1 !important;">
            Retour au Club
        </span>
    </a>
</div>
    </div>

</body>
</html>