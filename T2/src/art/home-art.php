<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'].'/config.php';

    $ID = "'".$_SESSION['ID_artista']."'";
    $stmt = "SELECT * FROM Artistas WHERE ID_artista=".$ID;
    $query = mysqli_query($conn,$stmt);
    $result = mysqli_fetch_assoc($query);
    $user = $result['nombre_artistico'];
?>

<!DOCTYPE>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
    <title>Poyofy : : Artista</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
  </head>

  <body>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/header.php'; ?>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/navigation-bar-art.php'; ?>

      <h3>Bienvenido <?= $user; ?></h3>
      <p>Estas logeado.</p>
        Para salir <a href="/logout.php">pulsa aqui</a>.
  </body>
</html>
