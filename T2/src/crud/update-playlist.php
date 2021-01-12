<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'].'/config.php';

    $ID = "'".$_SESSION['ID_usuID_artistaario']."'";
    $stmt = "SELECT * FROM Usuarios WHERE ID_usuario=".$ID;
    $query = mysqli_query($conn,$stmt);
    $result = mysqli_fetch_assoc($query);
    $user = $result['nombre_usuario'];
?>

<!DOCTYPE html>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
        <title>Poyofy : : Mis Playlists</title>
        <link rel="stylesheet" href="/assets/style.css">
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    </head>
    
    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/header.php'; ?>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/navigation-bar-user.php'; ?>
        <h3>Playlists seguidas por <?= $user; ?></h3>
        <?php
            if(!empty($_POST['nombre_cancion'])){
                $stmt = "SELECT * FROM Canciones WHERE nombre_cancion='".$_POST['nombre_cancion']."'";
                $query = mysqli_query($conn,$stmt);
                $result = mysqli_fetch_assoc($query);
                $time = $result['duracion'];
                $ID_cancion = $result['ID_cancion'];
                $values = "('".$_SESSION['ID_playlist']."','".$ID_cancion."')";
                
                $stmt = "INSERT INTO Playlists_Canciones(ID_playlist, ID_cancion) VALUES ".$values;
                $query1 = mysqli_query($conn,$stmt);
                
                $stmt = "SELECT * FROM Playlists WHERE ID_playlist='".$_SESSION['ID_playlist']."'";
                $query = mysqli_query($conn,$stmt);
                $result = mysqli_fetch_assoc($query);
                $total = $result['duracion_total'];
                $cont = $result['cantidad_canciones']+1;
                $stmt = "UPDATE Playlists SET duracion_total=ADDTIME('".$total."','".$time."'), cantidad_canciones='".$cont."' WHERE ID_playlist='".$_SESSION['ID_playlist']."'";
                $query2 = mysqli_query($conn,$stmt);
                
                if($query1 && $query2){
                    echo "<p style=\"color:red;\">cancion ".$_POST['nombre_cancion']." se ha agregado correctamente a la playlist ".$_SESSION['nombre_playlist']."</p>";
                }
                else{
                    echo "<p style=\"color:red;\">cancion ".$_POST['nombre_cancion']." no se ha agregado a la playlist ".$_SESSION['nombre_playlist']."</p>";
                }
            }
            $stmt = "SELECT * FROM Canciones";
            $query = mysqli_query($conn,$stmt);
            $cont = 0;
            echo "<p style=\"color:#337DFF;\">Elija la cancion a agregar a la ".$_SESSION['nombre_playlist']."</p>";
            if(mysqli_num_rows($query)>0){
                echo "<form action=\"/crud/update-playlist.php\" method=\"post\">";
                while($row=mysqli_fetch_assoc($query)){
                    $stmt = "SELECT * FROM Playlists_Canciones WHERE ID_cancion='".$row['ID_cancion']."' AND ID_playlist='".$_SESSION['ID_playlist']."'";
                    $query2 = mysqli_query($conn,$stmt);
                    if(mysqli_num_rows($query2)==0){
                        echo "<input type=\"submit\" value=\"".$row['nombre_cancion']."\" name=\"nombre_cancion\"><br>";
                        $cont++;
                    }
                }
                echo "</form>";
            }
            else{
                echo "<p style=\"color:red;\"> No hay mas canciones para agregar a la playlist."."</p><br>";
            }
            if($cont==0){
                echo "<p style=\"color:red;\"> No hay mas canciones para agregar a la playlist."."</p><br>";
            }
        ?>
        <br>
        <form><input type="button" onclick="window.location.href='/user/my-playlist.php';" value="Volver"></form>
    </body>
</html>