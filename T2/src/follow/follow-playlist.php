<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'].'/config.php';
    
    if(!empty($_SESSION['ID_artista'])){
        $ID = "'".$_SESSION['ID_artista']."'";
        $stmt = "SELECT * FROM Artistas WHERE ID_artista=".$ID;
        $query = mysqli_query($conn,$stmt);
        $result = mysqli_fetch_assoc($query);
        $user = $result['nombre_artistico'];
        $persona = $result['ID_persona'];
    }
    else if(!empty($_SESSION['ID_usuario'])){
        $ID = "'".$_SESSION['ID_usuario']."'";
        $stmt = "SELECT * FROM Usuarios WHERE ID_usuario=".$ID;
        $query = mysqli_query($conn,$stmt);
        $result = mysqli_fetch_assoc($query);
        $user = $result['nombre_usuario'];
        $persona = $result['ID_persona'];
    }
?>

<!DOCTYPE html>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
        <title>Poyofy : : Seguir Playlist</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/assets/style.css">
    </head>
    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/header.php'; ?>
        <?php
        if(!empty($_SESSION['ID_artista'])){
            require $_SERVER['DOCUMENT_ROOT'].'/partials/navigation-bar-art.php';
        } 
        else if(!empty($_SESSION['ID_usuario'])){
            require $_SERVER['DOCUMENT_ROOT'].'/partials/navigation-bar-user.php';
        } ?>
        
        <h3>Playlists seguidas por <?= $user; ?></h3>
        
        <?php
            $stmt = "SELECT * FROM Playlists_Personas WHERE ID_persona='".$persona."'";
            $query = mysqli_query($conn,$stmt);
            if(mysqli_num_rows($query)>0){ ?>
                <table class="center">
                    <tr>
                        <td>Nombre de la playlist</td>
                        <td>Cantidad de canciones</td>
                        <td>Duracion</td>
                        <td>Seguidores</td>
                    </tr>
                <?php
                    while($row = mysqli_fetch_assoc($query)){
                        $stmt = "SELECT * FROM Playlists WHERE ID_playlist='".$row['ID_playlist']."'";
                        $query2 = mysqli_query($conn,$stmt);
                        $result = mysqli_fetch_assoc($query2);
                        echo "<tr>";
                            echo "<td>".$result['nombre_playlist']."</td>";
                            echo "<td>".$result['cantidad_canciones']."</td>";
                            echo "<td>".$result['duracion_total']."</td>";
                            $stmt = "SELECT * FROM Playlists_Personas WHERE ID_playlist='".$row['ID_playlist']."'";
                            $query2 = mysqli_query($conn,$stmt);
                            $cont = mysqli_num_rows($query2);
                            echo"<td>".$cont."</td>";
                        echo "</tr>";
                    }
                ?>
                </table>
        <?php
            }
            else{
                echo "<p style=\"color:red;\">No sigues ninguna playlist</p>";
            }
        ?>
        <br><form><input type="button" onclick="history.back()" value="Volver"></form>
    </body>
</html>
