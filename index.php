<?php session_start();
const VERS = '0.4.0';
?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="UTF-8">
    <title>Cave</title>
    <meta name="viewport" content="width=device-width">
    <meta name="description" content="Stock en cours.">
    <link href="css/style.css" rel="stylesheet" type="text/css">
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
      crossorigin="anonymous"
    >
    <script
      defer
      src="https://unpkg.com/htmx.org@1.9.10"
      integrity="sha384-D1Kt99CQMDuVetoL1lrYwg5t+9QdHe7NLX/SoJYkXDFfX37iInKRy5xLSi8nO7UC"
      crossorigin="anonymous"
    ></script>



<?php
if (isset($_POST['name']) and isset($_POST['passwd'])) {
  $name = $_POST['name'];
  $passwd = $_POST['passwd'];
  $idsha = hash('sha256', $name.$passwd);

  if ($lines = file('datas/mdp.txt', FILE_IGNORE_NEW_LINES)) {
    foreach ($lines as $line) {
      if (strcmp($line, $idsha) == 0) {
        $_SESSION['connected'] = $idsha;
      }
      else {
        echo "Veuillez ajouter $idsha dans le fichier des mots de passe";
      }
    }
  }
  else {
    if (touch('datas/mdp.txt')) {
      $mdp = "$idsha\n";
      file_put_contents('datas/mdp.txt',$mdp , FILE_APPEND | LOCK_EX);
      $_SESSION['connected'] = $idsha;
    }
    else {
      echo "Le répertoire racine doit être accesible en écriture";
    }
  }
}
elseif (isset($_POST['quit'])) {
  unset($_SESSION['connected']);
}
?>


  </head>
  <body>
    <div class="container">
      <h1 class="text-center display-1 arizonia"><img src="css/logo1.webp" alt="Logo raisin" class="logo"> Cave</h1>
      <div class="mt-5 posts overflow-auto" data-hx-get="./api.php?action=get_posts" data-hx-trigger="load, create_post, update_post, modify_post">
      </div>
    </div>
    <footer class="footer mt-auto py-3 bg-light">
      <div class="container text-center">
        <span class="text-muted">Cave v <?php echo VERS; ?></span>
        <?php
        if (isset($_SESSION['connected'])) {
          echo <<<EOF
            <form method="post" action="./">
            <input type="hidden" name="quit" value="1">
            <input type="submit" class="btn btn-light" value="Se déconnecter">
            </form>
          EOF;
        }
        else {
          echo '<div class="container"><a class="btn btn-light" href="connect.php" role="button">Se connecter</a></div>';
        }
        ?>
      </div>
    </footer>
  </body>
</html>
