<?php
include("config.php");
include("email.php");

session_start();
if (!empty($_SESSION["id"])){
    $idf = $_SESSION["id"];
    $conn = conectar();
    $stmt = $conn->prepare("SELECT usuarios.id, usuarios.nombre_usuario, usuarios.correo_electronico, usuarios.rol, usuarios.fecha_creacion, fotos.enlace FROM usuarios LEFT JOIN fotos ON fotos.id_usuario = usuarios.id WHERE usuarios.id = ?;");
    $stmt->bind_param("i", $idf); 
    $stmt->execute(); 
    $stmt->bind_result($id, $nombre, $email, $rol, $fecha_creacion, $enlace); 
    $stmt->fetch(); 
    $nombrePartido = explode(" ", $nombre);
    $stmt->close(); 
    $conn->close();

}else{
    header("Location: index.php");
    exit();
}

//Actualizar datos
function updateUser($original){
    if(isset($_POST["passuser"])){
        $idactUser = $_POST["idactuser"];
        $nombrei = $_POST["nombuser"];
        $correo = $_POST["emailuser"];
        $contrasena = $_POST["passuser"];
        if(!empty($nombrei)||!empty($correo)){     
            if (validateEmail($correo)){
                if(empty($contrasena)){
                    echo "<p id=\"msg\">Introduce yor password.<p>";
                }else{
                    if(password_verify($contrasena, $original)){
                        $conn = conectar();    
                        $stmt = $conn->prepare("UPDATE usuarios SET nombre_usuario=?, correo_electronico = ? WHERE id=?");
                        $stmt->bind_param("ssi",$nombrei, $correo, $idactUser);
                        $stmt->execute(); 
                        $stmt->close(); 
                        $conn->close();
                        echo '<script type="text/javascript">alert("These data has been changed succesfully.");</script>';
                    echo '<script type="text/javascript">setTimeout(function(){window.location.href = "account.php";}, 500);</script>';
                    }else{
                        echo "<p id=\"msg\">This password doesn't match. Please verifiy it.<p>";
                    }
                } 
            }else{
                echo "<p id=\"msg\">This E-mail is't valid. Verify it.<p>";
            }

        }
        else{
            echo "<p id=\"msg\">Empty fields.<p>";
        }
    }
}

//Actualizar Clave
function changepass($original){
    if(isset($_POST["newpassuser2"])){
        $idactUser = $_POST["idactuser"];
        $origpass = $_POST["originalpassuser"];
        $newpassuser1 = $_POST["newpassuser1"];
        $newpassuser2 = $_POST["newpassuser2"];
        if(empty($origpass)&&empty($newpassuser1)&&empty($newpassuser2)){  
            echo "<p id=\"msg\">Empty fields.<p>";
        }
        else{
            if(empty($origpass)){  
                echo "<p id=\"msg\">Original Password field is empty.<p>";
            }else{
                if(empty($newpassuser1)&&empty($newpassuser2)){
                    echo "<p id=\"msg\">New password fields are empty.<p>";
                }else{
                    if(password_verify($origpass, $original)){
                        if( $newpassuser1 == $newpassuser2){
                            $conn = conectar();    
                            $stmt = $conn->prepare("UPDATE usuarios SET contrasena=? WHERE id=?");
                            $nuevacontrasena = password_hash($newpassuser2, PASSWORD_DEFAULT); 
                            $stmt->bind_param("si", $nuevacontrasena, $idactUser);
                            $stmt->execute(); 
                            $stmt->close(); 
                            $conn->close();
                            echo '<script type="text/javascript">alert("your password has been changed succesfully.");</script>';
                            echo '<script type="text/javascript">setTimeout(function(){window.location.href = "account.php";}, 500);</script>';
                        }
                        else{
                            echo "<p id=\"msg\">New password fields doesn't match. Please verifiy it.<p>";
                        }
                    }else{
                        echo "<p id=\"msg\">This password doesn't match. Please verifiy it.<p>";
                    }
                }
            }
        }
    }
}

function delAccount($original){
    if(isset($_POST["passuserdlt"])){
        $idactUser = $_POST["delaccount"];
        $contrasena = $_POST["passuserdlt"];
        if(password_verify($contrasena, $original)){
            $conn = conectar();    
            $stmt = $conn->prepare("DELETE FROM usuarios WHERE id=?");
            $stmt->bind_param("i", $idactUser);
            $stmt->execute(); 
            $stmt->close(); 
            $conn->close();
            echo '<script type="text/javascript">alert("We miss you.");</script>';
            session_start();
            session_unset();
            session_destroy();
            echo '<script type="text/javascript">setTimeout(function(){window.location.href = "index.php";}, 500);</script>';
            exit();
            
        }else{
            echo "<p id=\"msg\">This password doesn't match. Please verifiy it.<p>";
        }
    }         
}

function personalInformation(){
    $idf = $_SESSION["id"];
    $conn = conectar();
    $stmt = $conn->prepare("SELECT usuarios.id, usuarios.nombre_usuario, usuarios.correo_electronico, usuarios.contrasena, usuarios.fecha_creacion, fotos.enlace FROM usuarios LEFT JOIN fotos ON fotos.id_usuario = usuarios.id WHERE usuarios.id = ?;");
    $stmt->bind_param("i", $idf); 
    $stmt->execute(); 
    $stmt->bind_result($id, $nombre, $email, $contrasena, $fecha_creacion, $enlace); 
    $stmt -> fetch();
    $varphoto="";
    if  (!isset($enlace)){
        $varphoto="<span class=\"material-symbols-sharp\">person</span>";
    }else{
        $varphoto="<img id=\"imginfo\" src=\"$enlace\" alt=\"user_photo\">";
    }
    echo " <div id=\"personalInfo\">
            <p>change your pic, here it is:</p>
            <div id=\"photuser\">
                $varphoto
                <a href=\"\">Change</a>
            </div>
            <p>Change your personal information, here it is:</p>
            <form method=\"post\" id=\"infoForm\">
                <input type=\"hidden\" value=\"$id\" name=\"idactuser\">
                <label for=\"nombuser\">Name:</label>
                <input type=\"text\" value=\"$nombre\" name=\"nombuser\">
                <label for=\"emailuser\">E-mail:</label>
                <input type=\"text\" value=\"$email\" name=\"emailuser\">
                <label for=\"passuser\">Introduce yor password:</label>
                <input type=\"password\" value=\"\" name=\"passuser\">";
                if(isset($_POST["passuser"])){ updateUser($contrasena);}else{echo "</br>";}
                echo "<div id=\"butonarea\">
                    <button type=\"submit\" class=\"btn\" name=\"edts\"><span class=\"material-symbols-sharp\">update</span></button>
                </div>  
            </form>
            <p>Change your password, here it is:</p>
            <form method=\"post\" id=\"passwForm\">
                <input type=\"hidden\" value=\"$id\" name=\"idactuser\">
                <label for=\"originalpassuser\">Your original password:</label>
                <input type=\"password\" value=\"\" name=\"originalpassuser\">
                <label for=\"newpassuser1\">Your new password</label>
                <input type=\"password\" value=\"\" name=\"newpassuser1\">
                <label for=\"newpassuser1\">Confirmate new password</label>
                <input type=\"password\" value=\"\" name=\"newpassuser2\">";
                if(isset($_POST["newpassuser2"])){changePass($contrasena);}else{echo "</br>";}
                echo "<div id=\"butonarea\">
                    <button type=\"submit\" class=\"btn\" name=\"edts\"><span class=\"material-symbols-sharp\">update</span></button>
                </div> 
            </form>
            <p>Delete your account:</p>
            <form method=\"post\" id=\"passwForm\">
                <input type=\"hidden\" value=\"$idf\" name=\"delaccount\">
                <label for=\"passuserdlt\">Your password:</label>
                <input type=\"password\" value=\"\" name=\"passuserdlt\">";
                if(isset($_POST["passuserdlt"])){delAccount($contrasena);}else{echo "</br>";}
                echo "<div id=\"butonarea\">
                    <button type=\"submit\" name=\"edts\" id=\"delaccountbtn\"><span class=\"material-symbols-sharp\">delete</span></button>
                </div>
            </form>
        
    </div>
    </br>";     

    $stmt->close(); 
    $conn->close();
}



?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>living - Profile</title>
    <link rel="shortcut icon" href="./images/favicon.ico"/>
    <link rel="stylesheet" href="./styles/styleprofile.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Sharp:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
    <link href="https://fonts.googleapis.com/css2?family=Black+Ops+One&display=swap" rel="stylesheet">
</head>
<body class="general">
    <nav id="navbar">
        <div  id="logoNav">
            <img src="./images/favicon.ico" alt="logo" id="logo">
            <img src="./images/slogan2.png" alt="slogan" id="slogan">
        </div>
        <div id="searchNav">
            <form  action="" method="post" name="buscar" id="form_buscar">
                <input name="buscar" type="text" placeholder="Search in profile" id="input_buscar">
                <button type="submit" id="searchBtn">
                    <span class="material-symbols-sharp" id="texto_buscar">search</span>
				</button>
			</form>
        </div>
        <div id="profileNav">
            <form method="post" id="createpubForm" class="" >
                <input type="hidden" value=" <?php echo $id ?>" name="idcreatepublication" >
                <button type="submit" class="btncrtpbl" name="crts"><span class="material-symbols-sharp">add</span></button>
            </form>
            <span class="material-symbols-sharp onWall" id="notif">notifications</span>
            <a href="profile.php" id="profileimg">
                <?php if (!isset($enlace)){echo"<span class=\"material-symbols-sharp\">image</span>";}else{echo"<img id=\"img-profile\" src=\"$enlace\" alt=\"profile-photo\">";}?>
            </a>
        </div>
        <ul id="menu">
            <li>
                <a href="profile.php"><span class="material-symbols-sharp">person</span></a>
            </li>
            <li>
                <a href="talks.php"><span class="material-symbols-sharp">home</span></a>
            </li>
        </ul>
        <form action="endsession.php" method="post" id="endsession">
            <button type="submit" value="" id="btnlgt" name="logout">
                <span class="material-symbols-sharp">logout</span>
            </button>
        </form>
    </nav>
    <section id="container">
        <aside id= "configuration">
            <ul id="optionlist">
                <li id="titleconf">
                <span class="material-symbols-sharp">admin_panel_settings</span>Configuration
                </li>
                <li class="optionconf">
                    <span class="material-symbols-sharp">settings_accessibility</span>
                    <a href="profile.php">You and Living </a>
                </li>
                <li class="optionconf">
                    <span class="material-symbols-sharp">account_circle</span>
                    <a href="account.php">Your Account</a>
                </li>
                <?php if ($rol=="admin"){
                    echo '<li class="optionconf">
                    <span class="material-symbols-sharp">group</span>
                    <a href="users.php"><img src="./images/favicon.ico" alt="logo" id="logo">Users</a>   
                </li>';
                } ?>   
            </ul>
            <footer>
                <div id="footerInner">
                <p >Una creación de <span id="j">J</span><span id="h">H</span> 2024.</p>
                </div>
            </footer>
        </aside>
        <main> 
            <div id="greetings">
                <p>Hi! <?php echo "$nombrePartido[0]"?>, Welcome to your Configuration Panel </p>
            </div>
            <section id="board">
                <?php personalInformation(); ?>
                

                
</section>
            
        </main>
    </section> 
    
    
</body>
</html>