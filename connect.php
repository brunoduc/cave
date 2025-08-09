<!DOCTYPE html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <title>Cave</title>
    <meta name="viewport" content="width=device-width">
    <meta name="description" content="Connection au site.">
    <link
    href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    rel="stylesheet"
    integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN"
    crossorigin="anonymous"
    >
    <link href="./css/style.css" rel="stylesheet">
  </head>
  <body class="text-center">
    <header>
      <div class="container arizonia">
        <h1 class="text-center display-1"><img src="css/logo1.webp" alt="Logo raisin" class="logo"> Cave</h1>
        <h2>~ Connection ~</h2>
      </div>
    </header>
    <main>
    <form method="post" action="index.php">
      <div class="form-group col-sm-4">
        <input type="text" class="form-control" name="name" placeholder="Nom" autofocus><br/>
        <input type="password" class="form-control" name="passwd" placeholder="Mot de passe"><br/>
        <button class="btn btn-primary form-control" type="submit">Se connecter</button>
      </div>
    </form>
    </main>
  </body>
</html>
