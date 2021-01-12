<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'].'/config.php';

    $ID = "'".$_SESSION['ID_artista']."'";
    $stmt = "SELECT * FROM Artistas WHERE ID_artista=".$ID;
    $query = mysqli_query($conn,$stmt);
    $result = mysqli_fetch_assoc($query);
    $user = $result['nombre_artistico'];
    
    $stmt = "SELECT * FROM Albumes WHERE ID_artista=".$ID;
    $query = mysqli_query($conn,$stmt);
    
    $flag = false;
    if(mysqli_num_rows($query) > 0){
        while($row = mysqli_fetch_assoc($query)){
            if($row['ID_artista'] == $_SESSION['ID_artista']){
                $flag = true;
            }
        }
    }
?>

<!DOCTYPE html>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
        <title>Poyofy : : Mis Albumes</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/assets/style.css">
    </head>
    
    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/header.php'; ?>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/navigation-bar-art.php'; ?>
        <h3>Albumes de <?= $user; ?></h3>
        <?php 
            if(!empty($_POST['album'])){
                $value = "'".$_POST['album']."'";
                $stmt = "SELECT * FROM Albumes  WHERE nombre_album=".$value;
                $query = mysqli_query($conn, $stmt);
                $result = mysqli_fetch_assoc($query);
                $value = "'".$result['ID_album']."'";
                $_SESSION['ID_album'] = $result['ID_album'];
                $_SESSION['nombre_album'] = $_POST['album'];
                $stmt = "SELECT * FROM Canciones WHERE ID_album=".$value;
                $query = mysqli_query($conn, $stmt);
                
                if(mysqli_num_rows($query)>0){
                    echo "<h3>Canciones del album ".$_SESSION['nombre_album']."</h3>";?>
                    
                    <table class="center">
                        <tr>
                            <td>Nombre de la cancion</td>
                            <td>Duracion de la cancion</td>
                            <td>Genero de la cancion</td>
                        </tr>
                        <?php
                            $stmt = "SELECT * FROM Canciones WHERE ID_album='".$_SESSION['ID_album']."'";
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
                    <form action="/crud/update-album.php" method="post">
                        <input type="submit" value="Agregar cancion" name="add">
                    </form>
                    <br>
                    <form><input type="button" onclick="window.location.href='/art/my-album.php';" value="Volver"></form>
                <?php
                }
                else{
                    echo "<p style=\"color:red;\">Album ".$_POST['album']." no tiene canciones.</p><br>";
                    echo "<form action=\"/crud/update-album.php\" method=\"post\">";
                        echo "<input type=\"submit\" value=\"Agregar cancion\" name=\"add\">";
                    echo "</form>"; ?>
                    <form><input type="button" onclick="window.location.href='/art/my-album.php';" value="Volver"></form>
                <?php }
            } 
            else if(!empty($_POST['show'])){?>
            <h4>Seleccione album</h4>
            
            <?php
                $stmt = "SELECT * FROM Albumes WHERE ID_artista=".$ID;
                $query = mysqli_query($conn,$stmt);
                if(mysqli_num_rows($query) > 0){
                    echo "<form action=\"/art/my-album.php\" method=\"post\">";
                    while($row = mysqli_fetch_assoc($query)){
                        if($row['ID_artista'] == $_SESSION['ID_artista']){
                            echo "<input type=\"submit\" value=\"".$row['nombre_album']."\" name=\"album\"><br>";
                        }
                    }
                    echo "</form>"; ?>
                    <form><input type="button" onclick="window.location.href='/art/my-album.php';" value="Volver"></form>
                <?php
                }
            }else if($flag == false){ ?>
        
            <form action="/crud/create-album.php">
                <input type="submit" value="Crear Album">
                <p style="color:red;">No tiene albumes creados</p>
            </form>
        <?php }else{ ?>
        
            <table class="center">
                <tr>
                    <td>Nombre del album</td>
                    <td>Cantidad de canciones</td>
                    <td>Duracion del album</td>
                </tr>
                <?php
                    $stmt = "SELECT * FROM Albumes WHERE ID_artista=".$ID;
                    $query = mysqli_query($conn,$stmt);
                    while($row = mysqli_fetch_assoc($query)){
                        echo "<tr>";
                        echo "<td>".$row['nombre_album']."</td>";
                        echo "<td>".$row['cantidad_canciones']."</td>";
                        echo "<td>".$row['duracion_total']."</td>";
                        echo "</tr>";
                    }
                ?>
            </table>
            <br>
            <form action="/crud/create-album.php">
                <input type="submit" value="Crear Album">
            </form>
            <form action="/art/my-album.php" method="post">
                <input type="submit" value="Ver album" name="show">
            </form>
            <br>
            <form><input type="button" onclick="history.back()" value="Volver"></form>
        <?php } ?>
    </body>
</html>