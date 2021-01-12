<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'].'/config.php';
    
    $ID = "'".$_SESSION['ID_artista']."'";
    $stmt = "SELECT * FROM Artistas WHERE ID_artista=".$ID;
    $query = mysqli_query($conn,$stmt);
    $result = mysqli_fetch_assoc($query);
    $user = $result['nombre_artistico'];
    
    $stmt = "SELECT * FROM Canciones WHERE ID_artista=".$ID;
    $query = mysqli_query($conn,$stmt);
    
    $flag = false;
    while($row = mysqli_fetch_assoc($query)){
        if($row['ID_artista'] == $_SESSION['ID_artista']){
            $flag = true;
        }
    }
?>

<!DOCTYPE html>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
        <title>Poyofy : : Mis Canciones</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/assets/style.css">
    </head>
    
    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/header.php'; ?>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/navigation-bar-art.php'; ?>
        <h3>Canciones de <?= $user; ?></h3>
        <?php if($flag == false): ?>
            <form action="/crud/create-song.php">
                <p style="color:red;"><?= $user; ?> no tiene canciones creadas</p>
                <input type="submit" value="Crear Cancion">
            </form>
        <?php else: ?>
            
            <table class="center">
                <tr>
                    <td>Nombre de la cancion</td>
                    <td>Duracion de la cancion</td>
                    <td>Genero de la cancion</td>
                </tr>
                <?php
                    $stmt = "SELECT * FROM Canciones WHERE ID_artista=".$ID;
                    $query = mysqli_query($conn,$stmt);
                    while($row = mysqli_fetch_assoc($query)){
                        echo "<tr>";
                        echo "<td>".$row['nombre_cancion']."</td>";
                        echo "<td>".$row['duracion']."</td>";
                        echo "<td>".$row['genero']."</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
            <br>
            <form action="/crud/create-song.php">
                <input type="submit" value="Crear Cancion">
            </form>
            <form><input type="button" onclick="history.back()" value="Volver"></form>
        <?php endif; ?>
    </body>
</html>