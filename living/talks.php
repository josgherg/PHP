<?php
include("config.php");
include("publications.php");
include("comments.php");

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

publicate($_SESSION['id']);
publicateComments();


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
    header("Location: talks.php");
}


?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>living - Welcome</title>
    <link rel="shortcut icon" href="./images/favicon.ico"/>
    <link rel="stylesheet" href="./styles/styletalks.css">
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
                <input name="buscar" type="text" placeholder="Search in talks" id="input_buscar">
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
    <main id="talkswall"> 
        <div id="greetings">
            <p>Hi! <?php echo "$nombrePartido[0]"?>, Welcome to your talks </p>
        </div>
            <section id="board">
            <div id="createpublications">
                <?php $createPublications(); ?>
            </div>
            <div id="publications">
                <?php $showPublications($rol); ?>
            </div>
        </section>
        
    </main>
    <footer>
        <div id="footerInner">
          <p >Una creación de <span id="j">J</span><span id="h">H</span> 2024.</p>
        </div>
    </footer>
</body>
</html>