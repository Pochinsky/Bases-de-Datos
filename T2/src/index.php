<?php
  session_start();
  require $_SERVER['DOCUMENT_ROOT'].'/config.php';
?>

<!DOCTYPE html>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
    <title>Poyofy</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
  </head>

  <body>
    <?php require 'partials/header.php'; ?>

    <h3>Por favor, inicie sesion o cree una nueva cuenta</h3>

    <form>
      <input type="button" onclick="window.location.href='login/login.php';" value="Iniciar Sesion"><br>
      <input type="button" onclick="window.location.href='login/signup.php';" value="Registrarse">
    </form>
  </body>
</html>
