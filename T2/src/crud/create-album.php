<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'].'/config.php';
    
    $ID = "'".$_SESSION['ID_artista']."'";

    $msg = '';
    if(!empty($_POST['nombre'])){
        $name = "'".$_POST['nombre']."'";
        $values = "(".$name.",".$ID.")";
        $stmt = "INSERT INTO Albumes (nombre_album, ID_artista) VALUES ".$values;
        $query = mysqli_query($conn,$stmt);
        if($query){
            $msg = "Album ".$_POST['nombre']." ha sido creado correctamente.";
        }
        else{
            $msg = "Album ".$_POST['nombre']." no ha sido creado correctamente.";
        }
    }

    $stmt = "SELECT * FROM Artistas WHERE ID_artista=".$ID;
    $query = mysqli_query($conn,$stmt);
    $result = mysqli_fetch_assoc($query);
    $user = $result['nombre_artistico'];
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <title>Poyofy : : Crear Album</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/assets/style.css">
    </head>
    
    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/header.php'; ?>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/navigation-bar-art.php'; ?>
        
        <h3>Ingrese los datos</h3>
        <?php
            if(!empty($msg)){
                echo "<p style=\"color:red;\">".$msg."</p>";
            }
        ?>
        <form action="create-album.php" method="post">
            <input type="text" name="nombre" placeholder="Ingrese nombre del album">
            <input type="submit" value="Crear">
            <br>
            <input type="button" onclick="history.back()" value="Volver">
        </form>
    </body>
</html>