<?php
  session_start();
  require $_SERVER['DOCUMENT_ROOT'].'/config.php';

  if(isset($_SESSION['ID_persona'])){
    header('Location: /index.php');
  }

  if(!empty($_POST['user']) && !empty($_POST['password'])){
    //extraer ID y contraseña
    $user = "'".$_POST['user']."'";
    $stmt = "SELECT * FROM Usuarios WHERE nombre_usuario=".$user;
    $query = mysqli_query($conn,$stmt);
    $message = '';
    $hash = '';

    while($row = mysqli_fetch_assoc($query)){
      $hash = $row['password'];
      $ID = $row['ID_usuario'];
    }

    //verificar contraseña
    $pwd = $_POST['password'];
    if($pwd == $hash){
      $_SESSION['ID_usuario'] = $ID;
      header("Location: /user/home-user.php");
    }
    else{
      $message = "Error. Usuario/contrasena no coinciden";
    }
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

    <h3>Ingrese sus datos</h3>
    <?php if (!empty($message)): ?>
      <p style="color:#990000;"><?= $message; ?></p>
    <?php endif; ?>
    <span>Si no tiene cuenta: <a href="signup-user.php">registro de usuario</a></span><br>

    <form action="login-user.php" method="post">
      <input type="text" name="user" placeholder="Ingrese nombre de usuario">
      <input type="password" name="password" placeholder="Ingrese su contrasena">
      <input type="submit" value="Iniciar Sesion">
      <br>
      <input type="button" onclick="history.back()" value="Volver">
  </body>
</html>
