<?php
include("config.php");
include("email.php");

session_start();
if(!empty($_SESSION["id"])){
    header("Location: talks.php");
    exit();
}

$msg ="";
if(isset($_POST["login"])){
    $email= $_POST["email"];
    $clave= $_POST["pass"];
    if (validateEmail($email)){
        $conn=conectar();
        $stmt = $conn->prepare("SELECT id, nombre_usuario, contrasena, rol FROM usuarios  WHERE correo_electronico= ?"); 
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->bind_result($id, $nombre, $contrasena, $rol);
        $stmt->fetch(); 
        if(!isset($contrasena)){
            $msg ="This user doesn't exist in the database.";
        }else{
            if(password_verify($clave, $contrasena)){
                $_SESSION["login"]= true;
                $_SESSION["id"]= $id;
                header("Location: talks.php");
                
                exit();
            }else{
                $msg ="This password doesn't match. Please verifiy it.";
            }
        } 
        $conn->close();
        $stmt->close();
    }else{
        $msg ="This E-mail is't valid. Verify it.";
    }
}
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Enter or Register. </title>
    <link rel="shortcut icon" href="./images/favicon.ico"/>
    <link rel="stylesheet" href="styles/styleindex.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Black+Ops+One&display=swap" rel="stylesheet">
</head>
<body class="general">
    <main id="registerEnterRecover">
        <div id="divForm">
        <img src="./images/favicon.ico" alt="logo">
            <form action="" method="post" id="registerForm">
                
                <div id="inputs">
                    <span class="material-symbols-sharp formIcon" id="user" >account_circle</span>
                    <input type="text" name="email" id="usuario" placeholder="E-mail">
                    <span class="material-symbols-sharp formIcon" id="pass">Lock</span>
                    <input type="password" name="pass" id="clave" placeholder="password">      
                </div>
                <div id="message">
                <p><?php if(isset($_POST["login"])){echo $msg;}?></p>
                </div> 
                <div id="forgottenPass">
                    <a href="recover.php">Forgot your password?</a>
                </div>
                <div id=buttons>
                        <input type="submit" value="Login" class="btn" name="login"> 
                </div>
            </form>
            <form action="register.php" method="post" id="newaccountForm">
                <input type="submit" value="Create new account" class="btn" name="newAccount">
            </form>
        </div>
        <div id="divMessage">
            <img src="./images/slogan.png" alt="slogan" id="slogan">
            <p>"Much more than a network, it is a way of life"</p>
            <h2>Join us!</h2>
        </div>
    </main>
    <footer>
    <div id="footerInner">
          <p >Una creación de <span id="j">J</span><span id="h">H</span> 2024.</p>
        </div>
    </footer>
    
    
</body>
</html>