<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'connexion.php'; 

// 1. Récupération des courses (évite l'erreur si la table est vide)
$courses = [];
try {
    $query = $connexion->query("SELECT * FROM courses WHERE date_course >= CURDATE() ORDER BY date_course ASC");
    $courses = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Optionnel : logger l'erreur $e->getMessage();
}

// 2. Variable de connexion
$is_logged = (isset($_SESSION['username']) && !empty($_SESSION['username']));

// 3. Préparation de l'avatar (Seulement si connecté)
$avatar_url = ""; 
if ($is_logged) {
    $nav_seed = $_SESSION['user_avatar_seed'] ?? $_SESSION['username'];
    $avatar_url = "https://robohash.org/" . urlencode($nav_seed) . "?set=set4&size=50x50";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Calendrier des Courses | Running Club</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #f8f9fa;
            margin: 0; padding: 20px;
            color: #333;
        }
        .calendar-container { max-width: 900px; margin: 0 auto; position: relative; }
        
        /* Header propre */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            background: white;
            padding: 15px 25px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        .header h1 { margin: 0; font-size: 1.5rem; }

        /* Cartes de courses */
        .course-card {
            background: white;
            border-radius: 15px;
            padding: 20px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: 0.2s;
            border-left: 5px solid #007bff;
        }
        .course-card:hover { transform: translateX(10px); background: #f0f7ff; }

        .date-box {
            background: #007bff;
            color: white;
            padding: 10px;
            border-radius: 10px;
            text-align: center;
            min-width: 70px;
            margin-right: 20px;
        }
        .date-day { font-size: 1.5em; font-weight: bold; display: block; }
        .date-month { font-size: 0.8em; text-transform: uppercase; }

        .course-info { flex-grow: 1; }
        .course-info h3 { margin: 0; color: #1a1a1a; }
        .course-info p { margin: 5px 0 0; color: #666; font-size: 0.9em; }

        .distance-badge {
            background: #e9ecef;
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 0.8em;
            font-weight: bold;
            color: #495057;
        }
        .empty-state { text-align: center; padding: 50px; color: #999; }
        
        .nav-avatar {
            border-radius: 50%;
            border: 2px solid #007bff;
            width: 45px;
            height: 45px;
            display: block;
            transition: transform 0.2s;
        }
        .nav-avatar:hover { transform: scale(1.1); }
    </style>
</head>
<body>

<div class="calendar-container">
    <div class="header">
        <h1><i class="fa-regular fa-calendar-check"></i> Prochaines Courses</h1>
        
        <?php if ($is_logged): ?>
            <div style="display: flex; align-items: center; gap: 15px;">
                <a href="admin_panel.php">
                    <img src="<?php echo $avatar_url; ?>" class="nav-avatar" alt="Profil">
                </a>
            </div>
        <?php endif; ?>
    </div>

    <?php if (empty($courses)): ?>
        <div class="empty-state">
            <i class="fa-solid fa-person-running fa-3x"></i>
            <p>Aucune course prévue pour le moment. Repose-toi bien !</p>
        </div>
    <?php else: ?>
        <?php foreach ($courses as $c): 
            $dateObj = new DateTime($c['date_course']);
            $jour = $dateObj->format('d');
            $mois = strtoupper($dateObj->format('M'));
            $date_google = $dateObj->format('Ymd');
            
            $google_url = "https://www.google.com/calendar/render?action=TEMPLATE" .
                          "&text=" . urlencode("Course : " . $c['nom_course']) .
                          "&dates=" . $date_google . "/" . $date_google .
                          "&location=" . urlencode($c['lieu']) .
                          "&details=" . urlencode("Distance prévue : " . $c['distance']);
        ?>
            <a href="<?php echo $google_url; ?>" target="_blank" style="text-decoration: none; color: inherit;">
                <div class="course-card">
                    <div class="date-box">
                        <span class="date-day"><?php echo $jour; ?></span>
                        <span class="date-month"><?php echo $mois; ?></span>
                    </div>
                    
                    <div class="course-info">
                        <h3><?php echo htmlspecialchars($c['nom_course']); ?></h3>
                        <p><i class="fa-solid fa-location-dot"></i> <?php echo htmlspecialchars($c['lieu']); ?></p>
                        <small style="color: #007bff; font-size: 0.7em;">+ Ajouter à mon agenda</small>
                    </div>

                    <div class="distance-badge">
                        <?php echo htmlspecialchars($c['distance']); ?>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
    <?php endif; ?>

    <div style="margin-top: 30px; text-align: center;">
        <a href="index.php" style="color: #007bff; text-decoration: none; font-weight: bold;">
            <i class="fa-solid fa-arrow-left"></i> Retour à l'accueil
        </a>
    </div>
</div>
</body>
</html>