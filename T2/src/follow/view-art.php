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
        <title>Poyofy : : Encontrar Artista</title>
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
        
        <h3>Artistas de Poyofy</h3>
        <p style="color: #337DFF">Para seguir a un artista, clickeelo!</p>
        
        <?php 
            if(!empty($_POST['user'])){ 
                $stmt = "SELECT * FROM Artistas WHERE nombre_artistico='".$_POST['user']."'";
                $query = mysqli_query($conn,$stmt);
                $result = mysqli_fetch_assoc($query);
                $values = "('".$persona."','".$result['ID_persona']."')";
                $stmt = "INSERT INTO Personas_Personas(ID_persona1, ID_persona2) VALUES ".$values;
                $query = mysqli_query($conn,$stmt);
                if($query){
                    echo "<p style=\"color:red;\">Se ha seguido correctamente a ".$_POST['user']."</p>";
                }
                else{
                    echo "<p style=\"color:red;\">No se ha podido seguir a ".$_POST['user']." :(</p>";
                }
            }
            $cont=0;
            $stmt = "SELECT * FROM Artistas";
            $query = mysqli_query($conn,$stmt);
            if(mysqli_num_rows($query) > 0){
                echo "<form action=\"/follow/view-art.php\" method=\"post\">";
                while($row = mysqli_fetch_assoc($query)){
                    if(!empty($_SESSION['ID_artista'])){
                        if($_SESSION['ID_artista']!=$row['ID_artista']){
                            $stmt = "SELECT * FROM Personas_Personas WHERE ID_persona1='".$persona."' AND ID_persona2='".$row['ID_persona']."'";
                            $query2 = mysqli_query($conn,$stmt);
                            if(mysqli_num_rows($query2)==0){
                                echo "<input type=\"submit\" value=\"".$row['nombre_artistico']."\" name=\"user\"><br>";
                                $cont++;
                            }
                        }
                    }
                    else{
                        $stmt = "SELECT * FROM Personas_Personas WHERE ID_persona1='".$persona."' AND ID_persona2='".$row['ID_persona']."'";
                        $query2 = mysqli_query($conn,$stmt);
                        if(mysqli_num_rows($query2)==0){
                            echo "<input type=\"submit\" value=\"".$row['nombre_artistico']."\" name=\"user\"><br>";
                            $cont++;
                        }
                    }
                }
                echo "</form>";
                if($cont==0){
                    echo "<p style=\"color:red;\">No hay mas artistas que pueda seguir</p>";
                }
            ?>
                <form><input type="button" onclick="history.back()" value="Volver"></form>
            <?php
            }
            else{
                echo "<p style=\"color:red;\">Poyofy no tiene mas artistas D:</p>"; ?>
                <form><input type="button" onclick="history.back()" value="Volver"></form>
            <?php
            }
        ?>
    </body>
</html>