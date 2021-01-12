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
        <title>Poyofy : : Encontrar Playlist</title>
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
        
        <h3>Playlists de Poyofy</h3>
        
        <?php
            if(!empty($_POST['playlist'])){
                $stmt = "SELECT * FROM Playlists WHERE nombre_playlist='".$_POST['playlist']."'";
                $query = mysqli_query($conn,$stmt);
                $result = mysqli_fetch_assoc($query);
                $values = "('".$result['ID_playlist']."','".$persona."')";
                $stmt = "INSERT INTO Playlists_Personas(ID_playlist, ID_persona) VALUES ".$values;
                $query = mysqli_query($conn,$stmt);
                if($query){
                    echo "<p style=\"color:red;\">Ahora sigues la playlist ".$_POST['playlist']."</p>";
                }
                else{
                    echo "<p style=\"color:red;\">No se ha podido seguir la playlist</p>";
                }
            }
            if(!empty($_SESSION['ID_usuario'])){
                $stmt = "SELECT * FROM Playlists WHERE ID_usuario<>".$ID;
            }
            else{
                $stmt = "SELECT * FROM Playlists";
            }
            $query = mysqli_query($conn,$stmt);
            if(mysqli_num_rows($query)>0){
        ?>
                <p style="color:#337DFF;">Para seguir una playlist, clickee en el nombre!</p>
                <table class="center">
                    <tr>
                        <td>Nombre de la playlist</td>
                        <td>Creador</td>
                    </tr>
                <?php
                    $cont = 0;
                    while($row = mysqli_fetch_assoc($query)){
                        $stmt = "SELECT * FROM Usuarios WHERE ID_usuario='".$row['ID_usuario']."'";
                        $query2 = mysqli_query($conn,$stmt);
                        $result = mysqli_fetch_assoc($query2);
                        $creador = $result['nombre_usuario'];
                        $nombre = $row['nombre_playlist'];
                        $stmt = "SELECT * FROM Playlists_Personas WHERE ID_playlist='".$row['ID_playlist']."' AND ID_persona='".$persona."'";
                        $query2 = mysqli_query($conn,$stmt);
                        if(mysqli_num_rows($query2)==0){
                            $cont++;
                            echo "<tr>";
                                echo "<td>";
                                    echo "<form action=\"/follow/view-playlist.php\" method=\"post\">";
                                        echo "<input type=\"submit\" value=\"".$nombre."\" name=\"playlist\">";
                                    echo "</form>";
                                echo "</td>";
                                echo "<td>".$creador."</td>";
                            echo "</tr>";
                        }
                    }
                    if($cont==0){
                        echo "<tr><td>Sin playlits</td></tr>";
                    }
                ?>
                </table>
            <?php
                if($cont==0){
                    echo "<p style=\"color:red;\">No hay mas playlists para seguir</p>";
                }
            }
            else{
                echo "<p style=\"color:red;\">No hay playlists de otros usuarios D:</p>";
            }
        ?>
        <br><form><input type="button" onclick="history.back()" value="Volver"></form>
    </body>
</html>