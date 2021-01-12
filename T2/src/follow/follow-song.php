<?php
    session_start();
    require $_SERVER['DOCUMENT_ROOT'].'/config.php';
    
    $ID = "'".$_SESSION['ID_usuario']."'";
    $stmt = "SELECT * FROM Usuarios WHERE ID_usuario=".$ID;
    $query = mysqli_query($conn,$stmt);
    $result = mysqli_fetch_assoc($query);
    $user = $result['nombre_usuario'];
?>

<!DOCTYPE>

<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="utf-8">
        <title>Poyofy : : Canciones seguidas</title>
        <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="/assets/style.css">
    </head>

    <body>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/header.php'; ?>
        <?php require $_SERVER['DOCUMENT_ROOT'].'/partials/navigation-bar-user.php'; ?>
        <h3>Canciones seguidas por <?= $user; ?></h3>
        <?php
            if(!empty($_POST['seguir'])){
                $stmt = "SELECT * FROM Canciones WHERE nombre_cancion='".$_POST['seguir']."'";
                $query = mysqli_query($conn,$stmt);
                $result = mysqli_fetch_assoc($query);
                $stmt = "INSERT INTO Usuarios_Canciones(ID_usuario, ID_cancion) VALUES ('".$_SESSION['ID_usuario']."','".$result['ID_cancion']."')";
                $query= mysqli_query($conn,$stmt);
                if($query){
                    echo "<p style=\"color:red;\">".$user." ahora sigue la cancion ".$_POST['seguir']."</p><br>";
                }
                else{
                    echo "<p style=\"color:red;\"> No se ha podido seguir la cancion</p><br>";
                } ?>
                
                <?php
                    $stmt = "SELECT * FROM Usuarios_Canciones WHERE ID_usuario=".$ID;
                    $query = mysqli_query($conn,$stmt);
                    if(mysqli_num_rows($query)>0){ ?>
                        <table class="center">
                            <tr>
                                <td>Nombre de la cancion</td>
                                <td>duracion de la cancion</td>
                                <td>genero</td>
                            </tr>
                            <?php
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
                        <form action="/follow/follow-song.php" method="post">
                            <input type="submit" value="Seguir cancion" name="follow">
                        </form>
                        <input type="button" onclick="history.back()" value="Volver">
                <?php
                    }
            }
            else if(!empty($_POST['follow'])){ ?>
                <p style="color:#337DFF;">Clickea el nombre de la cancion que desea seguir.</p>
            <?php
                $stmt = "SELECT * FROM Canciones";
                $query = mysqli_query($conn,$stmt);
                if(mysqli_num_rows($query)>0){ ?>
                    <table class="center">
                        <tr>
                            <td>Nombre de la cancion</td>
                            <td>Artista compositor</td>
                        </tr>
                    <?php
                    while($row=mysqli_fetch_assoc($query)){
                        $stmt = "SELECT * FROM Usuarios_Canciones WHERE ID_usuario =".$ID." AND ID_CANCION='".$row['ID_cancion']."'";
                        $query3 = mysqli_query($conn,$stmt);
                        if(mysqli_num_rows($query3)==0){
                            $stmt = "SELECT * FROM Artistas WHERE ID_artista='".$row['ID_artista']."'";
                            $query2 = mysqli_query($conn,$stmt);
                            $result = mysqli_fetch_assoc($query2);
                            echo "<tr>";
                                echo "<td><form action=\"/follow/follow-song.php\" method=\"post\"><input type=\"submit\" value=\"".$row['nombre_cancion']."\" name=\"seguir\"></form></td>";
                                echo "<td>".$result['nombre_artistico']."</td>";
                            echo "</tr>";
                        }
                    }
                    ?>
                    </table>
                    <br>
                    <input type="button" onclick="window.location.href='/follow/follow-song.php';" value="Volver">
                    <?php
                    }
                else{
                    echo "<p style=\"color:red;\"> No hay canciones creadas.</p><br>";
                }
            }
            else{ ?>
            <?php
                $stmt = "SELECT * FROM Usuarios_Canciones WHERE ID_usuario=".$ID;
                $query = mysqli_query($conn,$stmt);
                if(mysqli_num_rows($query)>0){ ?>
                    <table class="center">
                        <tr>
                            <td>Nombre de la cancion</td>
                            <td>duracion de la cancion</td>
                            <td>genero</td>
                        </tr>
                        <?php
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
                <form action="/follow/follow-song.php" method="post">
                    <input type="submit" value="Seguir cancion" name="follow">
                </form>
                <input type="button" onclick="history.back()" value="Volver">
                <?php
                }
                else{
                    echo "<p style=\"color:red;\">".$user." no sigue ninguna cancion.</p><br>"; ?>
                    <form action="/follow/follow-song.php" method="post">
                        <input type="submit" value="Seguir cancion" name="follow">
                    </form>
                    <input type="button" onclick="history.back()" value="Volver">
                <?php
                }
            }
            ?>
    </body>
</html>
