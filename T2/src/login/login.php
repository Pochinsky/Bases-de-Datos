<?php
  session_start();

  if(isset($_SESSION['ID_persona'])){
    header('Location: /index.php');
  }
?>

<!DOCTYPE html>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
    <title>Poyofy : : Iniciar Sesion</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
  </head>

  <body>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/header.php'; ?>

    <h3>Seleccione tipo de sesion</h3>
    <span>si no tiene cuenta: <a href="signup.php">registrarse</a></span><br><br>

    <form>
      <input type="button" onclick="window.location.href='login-user.php'" name="user-user" value="Usuario" ><br>
      <input type="button" onclick="window.location.href='login-art.php'" name="user-art" value="Artista">
      <br>
      <input type="button" onclick="history.back()" value="Volver">
    </form>
  </body>
</html>
