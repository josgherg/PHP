<?php
include("config.php");
include("name.php");
include("email.php");
include("code.php");

session_start();
if(!empty($_SESSION["id"])){
    header("Location: talks.php");
    exit();
}

$msg2 =null;
if (!isset($_COOKIE["stage"])){
    $msg1 ="Introduce your E-mail here:";
    $msg3= "E-mail";
    if(isset($_POST["recover"])){
        $conn=conectar(); 
        $email= $_POST["emailCode"];
        if (validateEmail($email)){
            $stmt = $conn->prepare("SELECT id, nombre_usuario FROM usuarios WHERE correo_electronico=?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->bind_result($id, $nombre);
            $stmt->fetch();
            $stmt->close();
            if(isset($id)){
                $stmt = $conn->prepare("DELETE FROM recuperaciones WHERE id_usuario=?");
                $stmt->bind_param("s", $id);
                $stmt->execute();
                $code = codigoRecuperacion();
                recoveryEmail($email,$nombre, $code);
                $stmt = $conn->prepare("INSERT INTO recuperaciones (id_usuario, codigo) VALUES(?,?)");
                $stmt->bind_param("ss",$id, $code);
                $stmt->execute();
                $stmt->close();
                setcookie("id",$id, time() + 600, "/");
                //setcookie("token",$code, time() + 600, "/");
                setcookie("stage","1", time() + 600, "/");
                header("Location: recover.php");   
            }else{
                $msg2 ="This E-mail doesn't exist in the data base.";
            }     
        }else{
            $msg2 ="This E-mail is't valid. Verify it.";
        }
        $conn->close();
    }
}else{
    $conn=conectar();
    if ($_COOKIE["stage"]=="1"){
        $msg1 ="code was sent to yor E-mail. Introduce it here.";
        $msg3= "code";
        if(isset($_POST["recover"])){
            $code= $_POST["emailCode"];
            $stmt = $conn->prepare("SELECT codigo FROM recuperaciones WHERE id_usuario=?");
            $stmt->bind_param("s", $_COOKIE["id"]);
            $stmt->execute();
            $stmt->bind_result($cdg);
            $stmt->fetch();
            if($code ==$cdg){
                setcookie("stage","2", time() + 600, "/"); 
                header("Location: recover.php");
            }else{
                $msg2="This code is't correct. Please verify it.";
            }  
            $stmt->close();
        }        
    }else{
        $msg1 ="Introduce you new password  here.";
        $msg3= "password";
        if(isset($_POST["recover"])){
            $clave= $_POST["emailCode"];
            if (!empty($clave)){
                $conn=conectar(); 
                $stmt = $conn->prepare("UPDATE usuarios SET contrasena=? WHERE id=?");
                $convert = password_hash($clave, PASSWORD_DEFAULT);
                $stmt->bind_param("ss",$convert, $_COOKIE["id"]);
                $stmt->execute();
                setcookie("stage","2", time() - 600, "/");
                echo '<script type="text/javascript">alert("Your password has been changed succesfully.");</script>';
                echo '<script type="text/javascript">setTimeout(function(){window.location.href = "index.php";}, 500);</script>';
                $stmt->close();
                exit();
            }else{
                $msg2 ="This password is't valid. Verify it.";
            }
        }
    } 
    $conn->close();
} 
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recover your password</title>
    <link rel="shortcut icon" href="./images/favicon.ico"/>
    <link rel="stylesheet" href="styles/stylerecover.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Black+Ops+One&display=swap" rel="stylesheet">
</head>
<body class="general">
    <main id="Recover">
        <div id="divForm">
            <form action="" method="post" id="registerForm">
                <img src="./images/favicon.ico" alt="logo">
                <div id="inputs">
                    <p><?php echo $msg1?></p>
                    <span class="material-symbols-sharp formIcon" id="user" >account_circle</span>
                    <input type="text" name="emailCode" id="usuario" placeholder=<?php echo $msg3?>>
                </div>
                <div id="message">
                    <p><?php if(isset($msg2)){echo $msg2;}?></p>
                </div>
                <div id=buttons>
                    <input type="submit" value="send" class="btn" name="recover">
                </div>
            </form>
            <form action="index.php" method="post" id="backForm">
                <input type="submit" value="Go back" class="btn" name="back">
            </form>
        </div>
        <div id="divMessage">
            <img src="./images/slogan.png" alt="slogan" id="slogan">
            <p>Do you want to recover your password?</p>
            <p>validate you E-mail, then recieve the recovery code.</p>
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