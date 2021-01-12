<?php
  require $_SERVER['DOCUMENT_ROOT'].'/config.php';

  $message = '';
  if (!empty($_POST['nombre']) && !empty($_POST['apellido']) && !empty($_POST['user']) && !empty($_POST['password'])) {
    //Agregamos en Personas
    $values = "('".$_POST['nombre']."','".$_POST['apellido']."','".$_POST['sexo']."','".$_POST['edad']."')";
    $stmt = "INSERT INTO Personas(nombre,apellido,sexo,edad) VALUES ".$values;
    $query = mysqli_query($conn,$stmt);

    //Extraemos ID generada
    $name = "'".$_POST['nombre']."'";
    $lastn = "'".$_POST['apellido']."'";
    $stmt = "SELECT ID_persona FROM Personas WHERE nombre=".$name."AND apellido=".$lastn;
    $result = mysqli_query($conn,$stmt);
    $ID = 0;
    while($row = mysqli_fetch_assoc($result)){
      $ID = $row['ID_persona'];
    }

    //Agregamos en Artistas
    $username = "'".$_POST['user']."'";
    $password = "'".$_POST['password']."'";
    $values = "('".$ID."',".$username.",".$password.")";
    $stmt = "INSERT INTO Artistas(ID_persona,nombre_artistico,password) VALUES ".$values;
    $query2 = mysqli_query($conn,$stmt);

    if ($query && $query2) {
      $message = "Artista registrado correctamente. Dirigase a iniciar sesion";
    }
    else {
      $message = "Artista no registrado, revise datos ingresados";
    }
  }
?>

<!DOCTYPE html>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
    <title>Poyofy : : Registrarse</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
  </head>

  <body>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/header.php'; ?>
      <h3>Ingrese sus datos</h3>
    <?php if(!empty($message)): ?>
      <p style="color:#990000;"><?= $message ?></p>
    <?php endif; ?>

    <span>Si tiene cuenta: <a href="login-art.php">iniciar sesion como artista</a></span><br>

    <form action="signup-art.php" method="post">
      <input type="text" name="nombre" placeholder="Ingrese nombre">
      <input type="text" name="apellido" placeholder="Ingrese apellido">
      <input type="text" name="user" placeholder="Ingrese nombre artistico">
      <input type="text" name="sexo" placeholder="Ingrese sexo">
      <input type="number" name="edad" placeholder="Ingrese edad">
      <input type="password" name="password" placeholder="Ingrese contrasena">
      <input type="password" name="confirm_password" placeholder="Confirme contrasena">
      <input type="submit" value="Registrarse">
      <br>
      <input type="button" onclick="history.back()" value="Volver">
    </form>
  </body>
</html>
