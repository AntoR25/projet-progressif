<!DOCTYPE html>
<html lang="fr">


<!-- AJOUT, MODIFICATION ET SUPPRESSION DANS LA BASE DE DONNEE -->
<?php
session_start();

// SÉCURITÉ : On vérifie si l'utilisateur est connecté 
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

include("connexion.php");

// MODIFIER
if (isset($_POST['modificationvalide'])) {
    $id = $_POST['id'];
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    // Mise à jour dans la base de données (titre, contenu)
    $sql = "UPDATE articles SET artTitre=\"$titre\", artContenu=\"$contenu\" WHERE artID=\"$id\"";
    $connexion->exec($sql);
    echo "Article mis à jour";
}

// SUPPRIMER
if (isset($_POST['suppression'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM articles WHERE artID=\"$id\"";
    $connexion->exec($sql);
    echo "Article supprimé";
}

// AJOUTER
if (isset($_POST['insertion'])) {
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    // Insertion dans la base de données (titre, contenu)
    $sql = "INSERT INTO articles (artTitre, artContenu) VALUES ('$titre', '$contenu')";
    $connexion->exec($sql);
    echo "Article ajouté";
}
?>
<head>
    <title>ADMIN NEWS</title>
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
		p.hidden {
    display: none;
}
article#content a {
	color:#CA4D13;
	padding-top:0.8rem;
	font-size:0.8em;
}
        input[type="submit"] {
            appearance: none;
            background-color: transparent;
            border: 0.125em solid #1A1A1A;
            border-radius: 0.9375em;
            box-sizing: border-box;
            color: #3B3B3B;
            cursor: pointer;
            display: inline-block;
            font-family: Roobert, -apple-system, BlinkMacSystemFont, "Segoe UI", Helvetica, Arial, sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol";
            font-size: 10px;
            font-weight: 700;
            line-height: normal;
            margin: 0;
            min-height: 3.75em;
            min-width: 0;
            outline: none;
            padding: 1em 2.3em;
            text-align: center;
            text-decoration: none;
            transition: all 300ms cubic-bezier(.23, 1, 0.32, 1);
            user-select: none;
            -webkit-user-select: none;
            touch-action: manipulation;
            will-change: transform;
        }

        input[type="submit"]:hover {
            color: #fff;
            background-color: #1A1A1A;
            box-shadow: rgba(0, 0, 0, 0.25) 0 8px 15px;
            transform: translateY(-2px);
        }

        .article-buttons {
            display: flex;
            padding-right: 70vw;
        }
    </style>
</head>
    <div id="wrapper">

        <header>
            <figure id="logo">
                <img src="immages/empty.png" alt="" title="" width="200" height="200">

                <figcaption>
                    <h1>IFOSUP Running Club</h1>

                </figcaption>
            </figure>

            <!-- Nav -->
            <nav>
                <ul>
                    <li><a href="adminnews.php" class="lienactif">ADMIN-NEWS</a></li>
                    <li><a href="adminresults.php">ADMIN-RESULTS</a></li>
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
  <article id="content" style="width:95%;padding-left:4vw;">
    <h2>News</h2><br>
    <?php
    $select = $connexion->query("SELECT * FROM articles");
    while ($enregistrement = $select->fetch(PDO::FETCH_OBJ)) {
      if ((isset($_POST['modification'])) && ($enregistrement->artID == $_POST['id'])) {
        // Formulaire de modification pré-rempli
        ?>
        <form action="" method="post" enctype="multipart/form-data">
          Titre : <input type="text" name="titre" required value="<?php echo $enregistrement->artTitre; ?>"><br>										Contenu : <br>
          <textarea type="textarea" name="contenu" required><?php echo $enregistrement->artContenu; ?></textarea><br>
          <input type="hidden" name="id" value="<?php echo $enregistrement->artID; ?>">
          <input type="submit" value="Modifier" name="modificationvalide">
          <input type="submit" value="Annuler" name="annuler">
        </form>
        <?php
      } else {
        ?>
        <div id="article-<?php echo $enregistrement->artID; ?>" class="article-list">
          <h3><?php echo $enregistrement->artTitre; ?></h3>
		  <a href="#" class="read-more">Read more</a>
          <br><br>
          <p class="hidden"><?php echo $enregistrement->artContenu; ?></p>
          <div class="article-buttons">
            <form action="" method="post">
              <!-- Pour récupérer l'ID de l'article -->
              <input type="hidden" name="id" value="<?php echo $enregistrement->artID; ?>">
              <input type="submit" value="Modifier" name="modification">
            </form>
            <form action="" method="post" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette news ?');">
              <!-- Pour récupérer l'ID de l'article -->
              <input type="hidden" name="id" value="<?php echo $enregistrement->artID; ?>">
              <input type="submit" value="Supprimer" name="suppression">
            </form>
          </div>
        </div>
        <?php
      }
    }
    ?>
    <!-- Ajouter un article -->
    <hr>
    <h2>Ajouter une News</h2>
    <br>
    <form action="adminnews.php" method="post" enctype="multipart/form-data">
      Titre : <input type="text" name="titre" required><br>
      Contenu : <br>
      <textarea type="textarea" name="contenu" required></textarea><br><br>
      <input type="submit" value="Ajouter" name="insertion">
    </form>
  </article>
</section>

<script>
  const readMoreLinks = document.getElementsByClassName('read-more');

  Array.from(readMoreLinks).forEach(function(link) {
    link.addEventListener('click', function(event) {
      event.preventDefault();
      const hiddenContent = this.parentNode.querySelector('p.hidden');
      const currentDisplay = getComputedStyle(hiddenContent).display;

      if (currentDisplay === 'none') {
        hiddenContent.style.display = 'block';
        this.textContent = 'Read less';
      } else {
        hiddenContent.style.display = 'none';
        this.textContent = 'Read more';
      }
    });
  });
</script>
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