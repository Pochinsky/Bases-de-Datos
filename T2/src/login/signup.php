<!DOCTYPE>

<html>
  <head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
    <title>Poyofy : : Registrarse</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style.css">
  </head>

  <body>
    <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/header.php'; ?>

    <h3>Seleccione tipo de registro</h3>
    <span>Si tiene cuenta: <a href="login.php">iniciar sesion</a></span><br><br>

    <form>
      <input type="button" onclick="window.location.href='signup-user.php';" value="Usuario"><br>
      <input type="button" onclick="window.location.href='signup-art.php';" value="Artista">
      <br>
      <input type="button" onclick="history.back()" value="Volver">
    </form>
  </body>
</html>
