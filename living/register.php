<?php
include("config.php");
include("name.php");
include("email.php");

session_start();
if(!empty($_SESSION["id"])){
    header("Location: talks.php");
    exit();
}
//Se puedde colocar un else cuadno sea necesario

$msg ="";
if(isset($_POST["register"])){
    $nombre= $_POST["name"];
    $email= $_POST["email"];
    $clave= $_POST["pass"];
    
    if (validateName($nombre)){
        if (validateEmail($email)){
            if (!empty($clave)){
                $rol="user";
                $conn=conectar(); 
                $stmt = $conn->prepare("INSERT INTO usuarios (nombre_usuario, correo_electronico, contrasena, rol) VALUES(?, ?, ?, ?)");
                $convert = password_hash($clave, PASSWORD_DEFAULT);
                $stmt->bind_param("ssss", $nombre, $email , $convert, $rol);
                $stmt->execute();
                $_SESSION["login"]= true;
                $_SESSION["id"]= $id;
                header("Location: talks.php"); 
                welcomeEmail($email, $nombre);
                $conn->close();
                $stmt->close();
                exit();
            }else{
                $msg ="This password is't valid. Verify it.";
            }
        }else{
            $msg ="This E-mail is't valid. Verify it.";   
        }
    }else{
        $msg ="This name is't valid. Verify it.";   
    }
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Enter or Register. </title>
    <link rel="shortcut icon" href="./images/favicon.ico"/>
    <link rel="stylesheet" href="styles/styleregister.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Black+Ops+One&display=swap" rel="stylesheet">
</head>
<body class="general">
    <main id="register">
        <div id="divForm">
            <img src="./images/favicon.ico" alt="logo">
                <form action="" method="post" id="registerForm">
                    <div id="inputs">
                        <span class="material-symbols-sharp formIcon" id="name">person</span>
                        <input type="text" name="name" id="nombre" placeholder="Full name">
                        <span class="material-symbols-sharp formIcon" id="user" >account_circle</span>
                        <input type="text" name="email" id="usuario" placeholder="E-mail">
                        <span class="material-symbols-sharp formIcon" id="pass">Lock</span>
                        <input type="password" name="pass" id="clave" placeholder="password">      
                    </div>
                    <div id="message">
                        <p><?php if(!isset($_POST["login"])){echo $msg;}?></p>
                    </div> 
                    <div id=buttons>
                            <input type="submit" value="Register" class="btn" name="register"> 
                    </div>
            </form>
            <form action="index.php" method="post" id="backForm">
                <input type="submit" value="Go back" class="btn" name="back">
            </form>
        </div>
        <div id="divMessage">
            <img src="./images/slogan.png" alt="slogan" id="slogan">
            <p>"Register yourself with your name, E-mail and your password"</p>
            <h2>It's easy!</h2>
        </div>
    </main>
    <footer>
    <div id="footerInner">
          <p >Una creación de <span id="j">J</span><span id="h">H</span> 2024.</p>
        </div>
    </footer> 
</body>
</html>