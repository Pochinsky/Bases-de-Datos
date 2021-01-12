<?php
  session_start();
  require $_SERVER['DOCUMENT_ROOT'].'/config.php';

  $ID = "'".$_SESSION['ID_usuario']."'";
  $stmt = "SELECT * FROM Usuarios WHERE ID_usuario=".$ID;
  $query = mysqli_query($conn,$stmt);

  $result = mysqli_fetch_assoc($query);
  $user = $result['nombre_usuario'];
  $ID_persona = $result['ID_persona'];
  $stmt = "SELECT * FROM Personas WHERE ID_persona=".$ID_persona;
  $query = mysqli_query($conn,$stmt);

  $result = mysqli_fetch_assoc($query);
  $name = $result['nombre'];
  $lastn = $result['apellido'];
  $sex = $result['sexo'];
  $age = $result['edad'];

  $cont = 0;
  $stmt = "SELECT * FROM Personas_Personas WHERE ID_persona2=".$ID_persona;
  $query = mysqli_query($conn,$stmt);
  if(mysqli_num_rows($query)>0){
      while($row = mysqli_fetch_assoc($query)){
          $cont++;
      }
  }
?>

<!DOCTYPE>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1" charset="utf-8">
    <title>Poyofy : : Usuario</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
  </head>

  <body>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/header.php'; ?>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/navigation-bar-user.php'; ?>

    <h3>Perfil de <?= $user; ?></h3>
    <table class="center">
      <tr>
        <td>Nombre</td>
        <td><?= $name; ?></td>
      </tr>
      <tr>
        <td>Apellido</td>
        <td><?= $lastn; ?></td>
      </tr>
      <tr>
        <td>Sexo</td>
        <td><?= $sex ?></td>
      </tr>
      <tr>
        <td>Edad</td>
        <td><?= $age; ?></td>
      </tr>
      <tr>
        <td>Seguidores</td>
        <td><?= $cont; ?></td>
      </tr>
    </table><br>
    <br>

    <form action="/logout.php">
      <input type="submit" value="Cerrar Sesion">
    </form>
  </body>
</html>
