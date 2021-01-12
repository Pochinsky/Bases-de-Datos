<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'].'/config.php';
    
    $ID = "'".$_SESSION['ID_artista']."'";
    $stmt = "SELECT * FROM Artistas WHERE ID_artista=".$ID;
    $query = mysqli_query($conn,$stmt);
    $result = mysqli_fetch_assoc($query);
    $user = $result['nombre_artistico'];
    
    $message = '';
    if(!empty($_POST['name']) && !empty($_POST['minutos']) && !empty($_POST['segundos'])){
        $time = "'00:".$_POST['minutos'].":".$_POST['segundos']."'";
        $values = "('".$_POST['name']."',".$time.",'".$_POST['genero']."',".$ID.")";
        $stmt = "INSERT INTO Canciones(nombre_cancion, duracion, genero, ID_artista) VALUES ".$values;
        $query = mysqli_query($conn, $stmt);
        if($query){
            $message = "Cancion creada correctamente.";
        }
        else{
            $message = "Cancion no pudo ser creada correctamente.";
        }
    }
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <title>Poyofy : : Crear Cancion</title>
        <link rel="stylesheet" href="/assets/style.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    </head>
    
    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/header.php'; ?>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/navigation-bar-art.php'; ?>
        <h3>Ingrese los datos</h3>
        <?php if(!empty($message)): ?>
            <p style="color:red;"><?= $message ?></p>
        <?php endif; ?>
        
        <form action="/crud/create-song.php" method="post">
            <input type="text" name="name" placeholder="Ingrese nombre de cancion">
            <input type="number" name="minutos" placeholder="Ingrese cantidad de minutos">
            <input type="number" name="segundos" placeholder="Ingrese cantidad de segundas">
            <input type="text" name="genero" placeholder="Ingrese genero de cancion">
            <input type="submit" value="Crear cancion"><br>
        </form>
        <input type="button" onclick="history.back()" value="Volver">
    </body>
</html>