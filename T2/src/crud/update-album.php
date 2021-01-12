<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'].'/config.php';

    $ID = "'".$_SESSION['ID_artista']."'";
    $stmt = "SELECT * FROM Artistas WHERE ID_artista=".$ID;
    $query = mysqli_query($conn,$stmt);
    $result = mysqli_fetch_assoc($query);
    $user = $result['nombre_artistico'];
?>

<!DOCTYPE html>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
        <title>Poyofy : : Mis Albumes</title>
        <link rel="stylesheet" href="/assets/style.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    </head>
    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/header.php'; ?>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/navigation-bar-art.php'; ?>
        <h3>Albumes de <?= $user; ?></h3>
        <?php
            if(!empty($_POST['nombre_cancion'])){
                $stmt = "UPDATE Canciones SET ID_album='".$_SESSION['ID_album']."' WHERE nombre_cancion='".$_POST['nombre_cancion']."'";
                $query1 = mysqli_query($conn,$stmt);
                
                $stmt = "SELECT * FROM Canciones WHERE nombre_cancion='".$_POST['nombre_cancion']."'";
                $query = mysqli_query($conn,$stmt);
                $result = mysqli_fetch_assoc($query);
                $time = $result['duracion'];
                
                $stmt = "SELECT * FROM Albumes WHERE ID_album='".$_SESSION['ID_album']."'";
                $query = mysqli_query($conn,$stmt);
                $result = mysqli_fetch_assoc($query);
                $total = $result['duracion_total'];
                $cont = $result['cantidad_canciones']+1;
                $stmt = "UPDATE Albumes SET duracion_total=ADDTIME('".$total."','".$time."'), cantidad_canciones='".$cont."' WHERE ID_album='".$_SESSION['ID_album']."'";
                $query2 = mysqli_query($conn,$stmt);
                
                if($query1 && $query2){
                    echo "<p style=\"color:red;\">cancion ".$_POST['nombre_cancion']." se ha agregado correctamente al album ".$_SESSION['nombre_album']."</p>";
                }
                else{
                    echo "<p style=\"color:red;\">cancion ".$_POST['nombre_cancion']." no se ha agregado al album ".$_SESSION['nombre_album']."</p>";
                }
            }
            $ID_album = "'".$_SESSION['ID_album']."'";
            $stmt = "SELECT * FROM Canciones WHERE ID_artista=".$ID." AND (ID_album<>".$ID_album." OR ID_album IS NULL)";
            $query = mysqli_query($conn,$stmt);
            echo "<p style=\"color:#337DFF;\">Elija la cancion a agregar al album ".$_SESSION['nombre_album']."</p>";
            if(mysqli_num_rows($query)>0){
                echo "<form action=\"/crud/update-album.php\" method=\"post\">";
                while($row=mysqli_fetch_assoc($query)){
                    echo "<input type=\"submit\" value=\"".$row['nombre_cancion']."\" name=\"nombre_cancion\"><br>";
                }
                echo "</form>";
            }
            else{
                echo "<p style=\"color:red;\">".$user." no tiene mas canciones creadas."."</p>";
            }
        ?>
        <form><input type="button" onclick="window.location.href='/art/my-album.php';" value="Volver"></form>
    </body>
</html>