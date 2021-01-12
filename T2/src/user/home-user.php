<?php
  session_start();
  require $_SERVER['DOCUMENT_ROOT'].'/config.php';

  if(isset($_SESSION['ID_usuario'])){
    //realizar query
    $ID = "'".$_SESSION['ID_usuario']."'";
    $stmt = "SELECT * FROM Usuarios WHERE ID_usuario=".$ID;
    $result = mysqli_query($conn,$stmt);

    //extraer nombre de usuario
    $cont = 0;
    while($row = mysqli_fetch_assoc($result)){
      $username = $row['nombre_usuario'];
      $cont++;
    }
    //almacenarlo
    if($cont != 0){
      $user = null;
      if(count($result)>0){
        $user = $username;
      }
    }
  }
?>

<!DOCTYPE>

<html>
  <head>
    <meta charset="utf-8">
    <title>Poyofy : : Usuario</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
  </head>

  <body>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/header.php'; ?>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/navigation-bar-user.php'; ?>

    <h3>Bienvenido <?= $user; ?></h3>
    <h4>Estas logeado.</h4>
    <p>Para salir <a href="/logout.php">pulsa aqui</a>.</p>
  </body>
</html>
