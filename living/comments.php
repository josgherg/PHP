<?php
//Crear comentarios
function createComment($idC, $idU){
            $conn = conectar();
            $stmt = $conn->prepare("SELECT usuarios.id, usuarios.nombre_usuario, fotos.enlace FROM usuarios LEFT JOIN fotos ON fotos.id_usuario = usuarios.id WHERE usuarios.id = ?;");
            $stmt->bind_param("i", $idU); 
            $stmt->execute(); 
            $stmt->bind_result($id, $nombre, $enlace); 
            $stmt->fetch(); 
            $varphoto=null;
            if  (!isset($enlace)){
                $varphoto="<span class=\"material-symbols-sharp\">person</span>";
            }else{
                $varphoto="<img id=\"imgpub\" src=\"$enlace\" alt=\"user_photo\">";
            }
            echo " <div id=\"createcomment\">
                        <div id=\"descrcrtcomm\">
                            <div id=\"phpcrttxt\">
                                $varphoto
                                <p id=\"nmbcrtpub\">$nombre, Reply it!:</p>
                            </div>
                        </div>
                        <div id=\"conttext\">
                            <form method=\"post\" id=\"commcrtForm\" >
                                <textarea name=\"createcomment\" id=\"idcrtcomment\"></textarea>
                                <input type=\"hidden\" value=\"$idC\" name=\"idcrtcomment\">
                                <button type=\"submit\" class=\"btnpbl\" name=\"cmmts\" id=\"sendcommbtn\"><span class=\"material-symbols-sharp\">send</span></button>
                                <a href=\"talks.php\" class=\"btnpbl\"id=\"backcommbtn\"><span class=\"material-symbols-sharp\">close</span></a>   
                            </form>
                        </div>
                    </div>";   
};

//Cargar Comentarios
function showComments($id, $role){    
    if(isset($_POST["idcommpublication"])){
        $idClick = $_POST["idcommpublication"];
        $idUser = $_SESSION["id"];
        $conn = conectar();    
        $stmt = $conn->prepare("SELECT comentarios.id, comentarios.id_usuario, comentarios.contenido, comentarios.fecha_creacion, usuarios.nombre_usuario, fotos.enlace, publicaciones.id
        FROM comentarios LEFT JOIN usuarios ON comentarios.id_usuario = usuarios.id LEFT JOIN fotos ON comentarios.id_usuario = fotos.id LEFT JOIN publicaciones ON publicaciones.id = comentarios.id_publicacion WHERE publicaciones.id = ?;");
        $stmt->bind_param("s",$id);
        $stmt->execute(); 
        $stmt->bind_result($idcomm, $iduser, $comm, $datecomm, $usercomm, $photocomm, $idpublication); 
        while ($stmt->fetch()){
            if ($id==$idClick){
                $varphoto=null;
                if  (!isset($photocomm)){
                    $varphoto="<span class=\"material-symbols-sharp\">person</span>";
                }else{
                    $varphoto="<img id=\"imgcomm\" src=\"$photocomm\" alt=\"user_photo\">";
                }
                $varEdit="";
                $varDelete="";
                $editproperty="";
                if  ($idUser==$iduser ||$role=="admin"){
                    $varDelete="<form method=\"post\" id=\"delcommtForm\" >
                        <input type=\"hidden\" value=\"$idcomm\" name=\"iddelcomm\" >
                        <button type=\"submit\" class=\"btncommdlt\" name=\"edts\"><span class=\"material-symbols-sharp\">delete</span></button>
                    </form>";
                    if  ($idUser==$iduser ){
                        $varEdit = "<button type=\"submit\" class=\"btncommedt\" name=\"pbledt\"><span class=\"material-symbols-sharp\">edit</span></button>";
                    }
                }else{
                    $editproperty = "readonly";
                }
                echo "<div id=\"comment\">
                    <div id=\"descrcomm\">
                        <div id=\"phptxtcomm\">
                            $varphoto
                            <p id=\"nmbcomm\">$usercomm has replied:</p>
                        </div> 
                        <div class=\"icons\">
                            $varDelete
                        </div>
                    </div>
                    <form method=\"post\" id=\"contenidocomm\">
                        <textarea id=\"contPrincipal\" $editproperty name=\"contPrincipalcommedt\">$comm</textarea>
                        <input type=\"hidden\" value=\"$idcomm\" name=\"idcommedit\">
                        <p id=\"contdate\">$datecomm</p>
                        $varEdit               
                    </form>
                </div> ";
            } 
        }
        $stmt->close(); 
        $conn->close();
        if ($id==$idClick){
            createComment($idClick,$idUser);
        }
    }
}

//Publicar comentarios
function publicateComments(){
    if(isset($_POST["idcrtcomment"]) && isset($_POST["createcomment"])){
        $textPublication = $_POST["createcomment"];
        $click = $_POST["idcrtcomment"];
        $idU = $_SESSION['id'];
        $conn = conectar();    
        $stmt = $conn->prepare("INSERT INTO comentarios (id_publicacion, id_usuario, contenido) VALUES(?,?,?)");
        $stmt->bind_param("iis",$click, $idU, $textPublication);
        $stmt->execute(); 
        $stmt->close(); 
        $conn->close();
        header("Location: talks.php");
        showComments($click);
    }
}

//Editar comentarios
function editComments($id){
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
            header("Location: talks.php");
        }else{
            header("Location: talks.php");
        }
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
    header("Location: talks.php");
}

?>