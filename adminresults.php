<?php
session_start();

// SÉCURITÉ : On vérifie si l'utilisateur est connecté 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("connexion.php");

?>	
<!DOCTYPE html>
<html lang="fr">

<head>
<title>ADMIN RESULTS</title>
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




<link href='https://fonts.googleapis.com/css?family=Pacifico' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="css/reset.css" type="text/css" media="screen">
<link rel="stylesheet" type="text/css" href="css/styles.css">
	
<style>
section ul, ol {
	padding:0;
	}
article#content {
	padding-left:0;
	}
article#content h2 {
	padding-left:1rem
	}
	
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

<!-- Nav -->	
	<nav>
	<ul>
		<li><a href="adminnews.php">ADMIN-NEWS</a></li>
		<li><a href="adminresults.php" class="lienactif">ADMIN-RESULTS</a></li>
        <?php if(isset($_SESSION['username'])): ?>
                    <li class="nav-auth">
                        <a href="admin_panel.php" style="display: flex; align-items: center; gap: 10px; text-decoration: none;">
                            <span>MY ACCOUNT</span>
                            <?php $nav_seed = $_SESSION['user_avatar_seed'] ?? $_SESSION['username']; ?>
                            <img src="https://robohash.org/<?php echo urlencode($nav_seed); ?>?set=set4&size=40x40" 
                                 alt="Profil" style="border-radius: 50%; border: 1px solid #ccc; background: #eee;">
                        </a>
                    </li>
                <?php endif; ?>
	</ul>	
	</nav>	
<!-- Fin Nav -->	
	</header>

     
<section>
<article id="content" style="width:100%;">	
	
	<h2>Results : 30 mai 2026 </h2>
<ol id="offres">	
 <li>
 <h3>Classement :<button onclick="ouvrirPopup()" style="margin-left: 10px; background-color: #4CAF50; color: white; border: none; cursor: pointer;">Ajouter Coureur</button></h3>
<br>
<!-- AFFICHER LE TABLEAU DES COURREURS -->
<?php
$sql = "SELECT * FROM courreur ORDER BY CourTemps ASC";
$resultat = $connexion->query($sql);
?>

<?php
if ($resultat->rowCount() > 0) {
    echo "<table style='border-collapse: collapse;'>";
    echo "<tr><th style='border: 1px solid black; padding: 8px;'>Position</th><th style='border: 1px solid black; padding: 8px;'>Nom</th><th style='border: 1px solid black; padding: 8px;'>Prénom</th><th style='border: 1px solid black; padding: 8px;'>Temps</th><th style='border: 1px solid black; padding: 8px;'>Sexe</th><th style='border: 1px solid black; padding: 8px;'>Admin</th></tr>";
	
	$numeroLigne = 1; // Variable pour le numéro de ligne

    while ($row = $resultat->fetch()) {
        echo "<tr>";
		echo "<td style='border: 1px solid black; padding: 8px; text-align: center;'>" . $numeroLigne . "</td>"; 
		// Affichage du numéro de ligne
        echo "<td style='border: 1px solid black; padding: 8px;'>" . $row["CourNom"] . "</td>";
        echo "<td style='border: 1px solid black; padding: 8px;'>" . $row["CourPrenom"] . "</td>";
        echo "<td style='border: 1px solid black; padding: 8px; text-align: center;'>" . $row["CourTemps"] . "</td>";
		echo "<td style='border: 1px solid black; padding: 8px; text-align: center;'>" . $row["CourSexe"] . "</td>";
		echo "<td>";
        echo "<button onclick='modifierLigne(" . $row["CourID"] . ")'>Modifier</button>";
        echo "<button onclick='supprimerLigne(" . $row["CourID"] . ")'>Supprimer</button>";
        echo "</td>";
        echo "</tr>";
		
		$numeroLigne++; // Incrémentation du numéro de ligne
    }

    echo "</table>";
} else {
    echo "Aucun résultat trouvé.";
}
?>

<!-- AJOUTER ET MODIFIER -->	
<script>
    // Pop-up pour modifier un coureur (Centrée et à la bonne taille)
    function modifierLigne(id) {
        var w = 650;
        var h = 600;
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        
        window.open("modifier.php?id=" + id, 'popup_modif', 'width=' + w + ', height=' + h + ', top=' + top + ', left=' + left + ', scrollbars=yes');
    }

    // Pop-up pour ajouter un coureur (Centrée et à la bonne taille)
    function ouvrirPopup() {
        var w = 650;
        var h = 600;
        var left = (screen.width / 2) - (w / 2);
        var top = (screen.height / 2) - (h / 2);
        
        window.open('ajouter.php', 'popup_ajout', 'width=' + w + ', height=' + h + ', top=' + top + ', left=' + left + ', scrollbars=yes');
    }
</script>
	
<!-- SUPPRIMER -->	
<script>
//Recupération de l'id de la ligne afin de pouvoir la supprimer avec du SQL
    function supprimerLigne(id) {
        if (confirm("Êtes-vous sûr de vouloir supprimer cette ligne ?")) {
            // Envoyer une requête AJAX pour effectuer la suppression
            var xhr = new XMLHttpRequest();
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    // Mettre à jour la page si nécessaire
                    location.reload();
                }
            };
            xhr.open("POST", "", true); // Utilisez la même page pour traiter la suppression
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.send("action=supprimer&id=" + id);
        }
    }
</script>
<?php
    // Suppression effectué sur la page actuel grâce à la requête AJAX
    if (isset($_POST["action"])) {
    $action = $_POST["action"];
    $id = $_POST["id"];

	if ($action === "supprimer") {
    // Effectuer la suppression de la ligne correspondante dans la base de données
    $requeteSuppression = $connexion->prepare("DELETE FROM courreur WHERE CourID=:id");
    $requeteSuppression->bindParam(":id", $id);
    $requeteSuppression->execute();
	}
}
?>
    </li>
</article> 
</section>

<footer>
        <nav>
          <ul>
			<li><h3>Back to website :</h3></li>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="News.php">News</a></li>
            <li><a href="Results.php">Results</a></li>
            <li><a href="Contact.php">Contact</a></li><br><br>
          </ul>
        </nav>
      </div>
    </footer>

</div><!--  Fin du div wrapper -->

</body>
</html>