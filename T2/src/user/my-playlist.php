<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'].'/config.php';

    $ID = "'".$_SESSION['ID_usuario']."'";
    $stmt = "SELECT * FROM Usuarios WHERE ID_usuario=".$ID;
    $query = mysqli_query($conn,$stmt);
    $result = mysqli_fetch_assoc($query);
    $user = $result['nombre_usuario'];

    $stmt = "SELECT * FROM Playlists WHERE ID_usuario=".$ID;
    $query = mysqli_query($conn,$stmt);
    
    $flag = false;
    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            if($row['ID_usuario'] == $_SESSION['ID_usuario']){
                $flag = true;
            }
        }
    }
?>

<!DOCTYPE>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
        <title>Poyofy : : Mis Playlists</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/assets/style.css">
    </head>

    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/header.php'; ?>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/navigation-bar-user.php'; ?>
        <h3>Playlists de <?= $user;?></h3>
        <?php
            if(!empty($_POST['playlist'])){
                $value = "'".$_POST['playlist']."'";
                $stmt = "SELECT * FROM Playlists  WHERE nombre_playlist=".$value;
                $query = mysqli_query($conn, $stmt);
                $result = mysqli_fetch_assoc($query);
                $value = "'".$result['ID_playlist']."'";
                $_SESSION['ID_playlist'] = $result['ID_playlist'];
                $_SESSION['nombre_playlist'] = $_POST['playlist'];
                $stmt = "SELECT * FROM Playlists_Canciones WHERE ID_playlist=".$value;
                $query = mysqli_query($conn, $stmt);
                
                if(mysqli_num_rows($query)>0){
                    echo "<h4>Canciones de la playlist ".$_SESSION['nombre_playlist']."</h4>";?>
                    
                    <table class="center">
                        <tr>
                            <td>Nombre de la cancion</td>
                            <td>Duracion de la cancion</td>
                            <td>Genero de la cancion</td>
                        </tr>
                        <?php
                            $stmt = "SELECT * FROM Playlists_Canciones WHERE ID_playlist='".$_SESSION['ID_playlist']."'";
                            $query = mysqli_query($conn,$stmt);
                            while($row = mysqli_fetch_assoc($query)){
                                $stmt = "SELECT * FROM Canciones WHERE ID_cancion='".$row['ID_cancion']."'";
                                $query2 = mysqli_query($conn,$stmt);
                                $result = mysqli_fetch_assoc($query2);
                                echo "<tr>";
                                echo "<td>".$result['nombre_cancion']."</td>";
                                echo "<td>".$result['duracion']."</td>";
                                echo "<td>".$result['genero']."</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
                    <br>
                    <form action="/crud/update-playlist.php" method="post">
                        <input type="submit" value="Agregar cancion" name="add">
                    </form>
                    <form>
                        <input type="button" onclick="window.location.href='/user/my-playlist.php';" value="Volver">
                    </form>
                <?php
                }
                else{
                    echo "<p style=\"color:red;\">Playlist ".$_POST['playlist']." no tiene canciones.</p><br>";
                    echo "<form action=\"/crud/update-playlist.php\" method=\"post\">";
                        echo "<input type=\"submit\" value=\"Agregar cancion\" name=\"add\">";
                    echo "</form>"; ?>
                    <form><input type="button" onclick="window.location.href='/user/my-playlist.php';" value="Volver"></form>
                <?php }
            }
            else if(!empty($_POST['show'])){ ?>
                <h4>Seleccione playlist</h4>
            
            <?php
                $stmt = "SELECT * FROM Playlists WHERE ID_usuario=".$ID;
                $query = mysqli_query($conn,$stmt);
                if(mysqli_num_rows($query) > 0){
                    echo "<form action=\"/user/my-playlist.php\" method=\"post\">";
                    while($row = mysqli_fetch_assoc($query)){
                        if($row['ID_usuario'] == $_SESSION['ID_usuario']){
                            echo "<input type=\"submit\" value=\"".$row['nombre_playlist']."\" name=\"playlist\"><br>";
                        }
                    }
                    echo "</form>"; ?>
                    <form>
                        <input type="button" onclick="window.location.href='/user/my-playlist.php';" value="Volver">
                    </form>
            <?php
                }
            }
            else{
                $stmt = "SELECT * FROM Playlists WHERE ID_usuario=".$ID;
                $query = mysqli_query($conn,$stmt);
                if(mysqli_num_rows($query)>0){ ?>
                    <table class="center">
                        <tr>
                            <td>Nombre de la playlist</td>
                            <td>Cantidad de canciones</td>
                            <td>Duracion de la playlist</td>
                            <td>Seguidores</td>
                        </tr>
                        <?php
                            $stmt = "SELECT * FROM Playlists WHERE ID_usuario=".$ID;
                            $query = mysqli_query($conn,$stmt);
                            while($row = mysqli_fetch_assoc($query)){
                                echo "<tr>";
                                    echo "<td>".$row['nombre_playlist']."</td>";
                                    echo "<td>".$row['cantidad_canciones']."</td>";
                                    echo "<td>".$row['duracion_total']."</td>";
                                    $stmt = "SELECT * FROM Playlists_Personas WHERE ID_playlist='".$row['ID_playlist']."'";
                                    $query2 = mysqli_query($conn,$stmt);
                                    $cont = mysqli_num_rows($query2);
                                    echo "<td>".$cont."</td>";
                                echo "</tr>";
                            }
                        ?>
                    </table>
                    <br>
                    <form action="/crud/create-playlist.php">
                        <input type="submit" value="Crear playlist">
                    </form>
                    <form action="/user/my-playlist.php" method="post">
                        <input type="submit" value="Ver playlist" name="show">
                    </form>
                    <input type="button" onclick="history.back()" value="Volver">
                <?php 
                }
                else{ ?>
                    <p style="color:red;">No tiene playlists creadas</p>
                    <form action="/crud/create-playlist.php">
                        <input type="submit" value="Crear playlist">
                    </form>      
                    <input type="button" onclick="history.back()" value="Volver">
            <?php
                }
            }
            ?>
    </body>
</html>
