<?php
include("config.php");

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


function yourPublications($id) {
    $idf = $_SESSION["id"];
    $conn = conectar();
    $stmt = $conn->prepare("SELECT id, contenido, fecha_creacion,fecha_actualizacion FROM publicaciones WHERE id_usuario = ? ORDER BY fecha_creacion");
    $stmt->bind_param("i", $idf); 
    $stmt->execute(); 
    $stmt->bind_result($id, $content, $fcreate, $fupdate); 
    $publications = [];
    while ($stmt -> fetch()) {
        $publications[] = [$id, $content, $fcreate, $fupdate];
    }
    $orderedPublications = array_reverse($publications);
    foreach ($orderedPublications as $publication){
        echo " <div id=\"publication\">
                    <div id=\"descrpub\">
                        <div id=\"phptxt\">
                            
                            <p id=\"nmbpub\">On $publication[2], you said:</p>
                        </div>
                        <div class=\"icons\">
                            <form method=\"post\" id=\"editpubtForm\">
                                <input type=\"hidden\" value=\"$publication[0]\" name=\"iddelpublication\">
                                <button type=\"submit\" class=\"btnpbldlt\" name=\"edts\"><span class=\"material-symbols-sharp\">delete</span></button>
                            </form>
                        </div>
                    </div>
                    <form method=\"post\" id=\"contenidopub\">
                        <textarea id=\"contPrincipal\" name=\"contPrincipaledt\">$publication[1]</textarea>
                        <input type=\"hidden\" value=\"$publication[0]\" name=\"idpublicationedit\">
                        <p id=\"contdate\">Last update: $publication[3]</p>
                        <button type=\"submit\" class=\"btnpbledt\" name=\"pbledt\"><span class=\"material-symbols-sharp\">edit</span></button>
                    </form>
                </div>
                </br>";   
    }
    $stmt->close(); 
    $conn->close();
}

//Editar publicaiones

if(isset($_POST["idpublicationedit"])){
    $textPublication = $_POST["contPrincipaledt"];
    if(!empty($textPublication)){  
        $idPublication = $_POST["idpublicationedit"];
        $conn = conectar();    
        $stmt = $conn->prepare("UPDATE publicaciones SET contenido=? WHERE id=?");
        $stmt->bind_param("si",$textPublication, $idPublication);
        $stmt->execute(); 
        $stmt->close(); 
        $conn->close();
        header("Location: profile.php");
    }else{
        header("Location: profile.php");
    }

}

//Eliminar publicaciones
if(isset($_POST["iddelpublication"])){
    $idPublication = $_POST["iddelpublication"];
    $conn = conectar();    
    $stmt = $conn->prepare("DELETE FROM publicaciones  WHERE id=?");
    $stmt->bind_param("i", $idPublication);
    $stmt->execute(); 
    $stmt = $conn->prepare("DELETE FROM comentarios  WHERE id_publicacion=?");
    $stmt->bind_param("i", $idPublication);
    $stmt->execute(); 
    $stmt->close(); 
    $conn->close();
    header("Location: profile.php");
}
function yourComments($id) {
    $idf = $_SESSION["id"];
    $conn = conectar();
    $stmt = $conn->prepare("SELECT publicaciones.id, publicaciones.id_usuario, comentarios.id, comentarios.id_publicacion, comentarios.id_usuario, comentarios.contenido, comentarios.fecha_creacion,comentarios.fecha_actualizacion, usuarios.nombre_usuario, usuarios.id FROM publicaciones LEFT JOIN comentarios  ON publicaciones.id= comentarios.id_publicacion LEFT JOIN usuarios ON publicaciones.id_usuario = usuarios.id WHERE comentarios.id_usuario = ? ORDER BY usuarios.id");
    $stmt->bind_param("i", $idf); 
    $stmt->execute(); 
    $stmt->bind_result($Pid, $PidU, $Cid, $CidP, $CidU, $commCont, $commFC, $commFU, $UnomU, $Uid); 
    $publications = [];
    while ($stmt -> fetch()) {
        $publications[] = [$Pid, $PidU, $Cid, $CidP, $CidU, $commCont, $commFC, $commFU, $UnomU, $Uid];
    }
    $orderedPublications = array_reverse($publications);
    foreach ($orderedPublications as $publication){
        echo " <div id=\"comment\">
                    <div id=\"descrcomm\">
                        <div id=\"phptxtcomm\">
                            
                            <p id=\"nmbcomm\">On $publication[6], you replied to $publication[8]:</p>
                        </div>
                        <div class=\"icons\">
                            <form method=\"post\" id=\"delcommtForm\">
                                <input type=\"hidden\" value=\"$publication[2]\" name=\"iddelcomm\">
                                <button type=\"submit\" class=\"btncommdlt\" name=\"edts\"><span class=\"material-symbols-sharp\">delete</span></button>
                            </form>
                        </div>
                    </div>
                    <form method=\"post\" id=\"contenidocomm\">
                        <textarea id=\"contPrincipal\" name=\"contPrincipalcommedt\">$publication[5]</textarea>
                        <input type=\"hidden\" value=\"$publication[2]\" name=\"idcommedit\">
                        <p id=\"contdate\">Last update: $publication[7]</p>
                        <button type=\"submit\" class=\"btncommedt\" name=\"btncommedt\"><span class=\"material-symbols-sharp\">edit</span></button>
                    </form>
                </div>
                </br>";   
    }
    $stmt->close(); 
    $conn->close();
}

//Editar comentarios
if(isset($_POST["idcommedit"])){
    $textComment = $_POST["contPrincipalcommedt"];
    if(!empty($textComment)){  
        $idComment = $_POST["idcommedit"];
        $conn = conectar();    
        $stmt = $conn->prepare("UPDATE comentarios SET contenido=? WHERE id=?");
        $stmt->bind_param("si",$textComment, $idComment);
        $stmt->execute(); 
        $stmt->close(); 
        $conn->close();
        header("Location: profile.php");
    }else{
        header("Location: profile.php");
    }
}

//Eliminar publicaciones
if(isset($_POST["iddelcomm"])){
    $idPublication = $_POST["iddelcomm"];
    $conn = conectar();    
    $stmt = $conn->prepare("DELETE FROM comentarios  WHERE id=?");
    $stmt->bind_param("i", $idPublication);
    $stmt->execute(); 
    $stmt->close(); 
    $conn->close();
    header("Location: profile.php");
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
                    <a href="account.php"><img src="./images/favicon.ico" alt="logo" id="logo">Your Account</a>
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
                <p class="title">Your Publications: </p>
                <?php yourPublications($_SESSION["id"]); ?>
                <p class="title">Your Comments: </p>
                <?php yourComments($_SESSION["id"]); ?>
                

                
</section>
            
        </main>
    </section> 
    
    
</body>
</html>