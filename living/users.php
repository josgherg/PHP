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

function users() {
    $idf = $_SESSION["id"];
    $conn = conectar();
    $stmt = $conn->prepare("SELECT usuarios.id, usuarios.nombre_usuario, usuarios.correo_electronico, usuarios.rol, usuarios.fecha_creacion, fotos.enlace FROM usuarios LEFT JOIN fotos ON fotos.id_usuario = usuarios.id ORDER by usuarios.nombre_usuario");
    $stmt->execute(); 
    $stmt->bind_result($id, $nombre, $email, $rol, $fecha_creacion, $enlace); 
    $users = [];
    while ($stmt -> fetch()) {
        $users[] = [$id, $nombre, $email, $rol, $fecha_creacion, $enlace];
    }
    $varphoto="";
    foreach ($users as $user){
        if  (!isset($user[5])){
            $varphoto="<span class=\"material-symbols-sharp\">person</span>";
        }else{
            $varphoto="<img id=\"imguser\" src=\"$user[5]\" alt=\"user_photo\">";
        }
        if($user[0] != $idf){
            echo " <div id=\"user\">
                <div id=\"descruser\">
                    <div id=\"phpusertxt\">
                        $varphoto
                        <h2 id=\"nmbuser\">$user[1]</h2>
                    </div>
                    <div class=\"icons\">
                        <form method=\"post\" id=\"deluserForm\">
                            <input type=\"hidden\" value=\"$user[0]\" name=\"iddeluser\">
                            <button type=\"submit\" class=\"btnuserdlt\" name=\"edts\"><span class=\"material-symbols-sharp\">delete</span></button>
                        </form>
                    </div>
                </div>
                <textarea id=\"contPuser\" readonly name=\"contPrincipaledt\">Email: $user[2]\nRole: $user[3]\nSince: $user[4]\n </textarea>
            </div>
            </br>";  
        } 
    }
    $stmt->close(); 
    $conn->close();
}



//Eliminar publicaciones
if(isset($_POST["iddeluser"])){
    $idDelUser = $_POST["iddeluser"];
    $conn = conectar();    
    $stmt = $conn->prepare("DELETE FROM usuarios  WHERE id=?");
    $stmt->bind_param("i", $idDelUser);

    $stmt->execute(); 
    $stmt->close(); 
    $conn->close();
    header("Location: users.php");
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
                    <a href="users.php">Users</a>   
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
                <?php users(); ?>
                

                
</section>
            
        </main>
    </section> 
    
    
</body>
</html>