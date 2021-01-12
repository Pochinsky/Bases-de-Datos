<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'].'/config.php';
    
    $ID = "'".$_SESSION['ID_usuario']."'";

    $message = '';
    if(!empty($_POST['playlist'])){
        $name = "'".$_POST['playlist']."'";
        $values = "(".$name.",".$ID.")";
        $stmt = "INSERT INTO Playlists (nombre_playlist, ID_usuario) VALUES ".$values;
        $query = mysqli_query($conn,$stmt);
        if($query){
            $message = "<p style=\"color:red;\">Playlist ".$_POST['playlist']." se ha creado correctamente.</p><br>";
        }
        else{
            $message = "<p style=\"color:red;\">Playlist ".$_POST['playlist']." no se ha creado correctamente.</p><br>";
        }
    }

    $stmt = "SELECT * FROM Usuarios WHERE ID_usuario=".$ID;
    $query = mysqli_query($conn,$stmt);
    $result = mysqli_fetch_assoc($query);
    $user = $result['nombre_usuario'];
?>

<!DOCTYPE html>

<html>
    <head>
        <meta charset="utf-8">
        <title>Poyofy : : Crear playlist</title>
        <link rel="stylesheet" href="/assets/style.css">
    </head>
    
    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/header.php'; ?>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/navigation-bar-user.php'; ?>
        
        <?php
            if(!empty($message)){
                echo $message;
            }
        ?>
        <h3>Ingrese los datos</h3>
        <form action="create-playlist.php" method="post">
            <input type="text" name="playlist" placeholder="Ingrese nombre de la playlist">
            <input type="submit" value="Crear">
            <br>
            <input type="button" onclick="history.back()" value="Volver">
        </form>
    </body>
</html>