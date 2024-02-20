<?php
//Cargar Publicaciones
$showPublications = function($role){
    if((!empty($_SESSION["id"]))){
        $iduser = $_SESSION["id"];
        $conn = conectar();
        $stmt = $conn->prepare("SELECT publicaciones.id, publicaciones.id_usuario, publicaciones.fecha_creacion, publicaciones.contenido, usuarios.nombre_usuario, fotos.enlace FROM publicaciones LEFT JOIN usuarios ON publicaciones.id_usuario = usuarios.id LEFT JOIN fotos ON publicaciones.id_usuario = fotos.id_usuario ORDER BY publicaciones.id");
        $stmt->execute(); 
        $stmt->bind_result($idPub, $idUser, $datePub, $contPub, $usuarioPub, $fotPub); 
        $publications = [];
        while ($stmt -> fetch()) {
            $publications[] = [$idPub, $idUser, $datePub, $contPub, $usuarioPub ,$fotPub];
        }
        $orderedPublications = array_reverse($publications);
        foreach ($orderedPublications as $publication){
            $varphoto=null;
            if  (!isset($publication[5])){
                $varphoto="<span class=\"material-symbols-sharp\">person</span>";
            }else{
                $varphoto="<img id=\"imgpub\" src=\"$publication[5]\" alt=\"user_photo\">";
            }
            $varEdit="";
            $varDelete="";
            $editproperty="";
            if  (($publication[1]==$iduser)||($role=="admin")){
                $varDelete="<form method=\"post\" id=\"editpubtForm\">
                    <input type=\"hidden\" value=\"$publication[0]\" name=\"iddelpublication\">
                    <button type=\"submit\" class=\"btnpbldlt\" name=\"edts\"><span class=\"material-symbols-sharp\">delete</span></button>
                </form>";
                if  ($publication[1]==$iduser){
                    $varEdit = "<button type=\"submit\" class=\"btnpbledt\" name=\"pbledt\"><span class=\"material-symbols-sharp\">edit</span></button>";
                }
            }else{
                $editproperty ="readonly";
            }
            echo " <div id=\"publication\">
                        <div id=\"descrpubarea\">
                            <div id=\"descrpub\">
                                <div id=\"phptxt\">
                                    $varphoto
                                    <p id=\"nmbpub\">$publication[4] said:</p>
                                </div>
                                <div class=\"icons\">
                                    $varDelete
                                    <form method=\"post\" id=\"commentForm\">
                                        <input type=\"hidden\" value=\"$publication[0]\" name=\"idcommpublication\">
                                        <button type=\"submit\" class=\"btnpblcmt\" name=\"reply\"><span class=\"material-symbols-sharp\">reply</span></button>
                                    </form>
                                </div>
                            </div>
                            <form method=\"post\" id=\"contenidopub\">
                                <textarea id=\"contPrincipal\" $editproperty name=\"contPrincipaledt\">$publication[3]</textarea>
                                <input type=\"hidden\" value=\"$publication[0]\" name=\"idpublicationedit\">
                                <p id=\"contdate\">$publication[2]</p>
                                $varEdit
                            </form>
                        </div>
                        <div id=\"comments\">";
                            showComments($publication[0],$role);
                        echo"</div>
                    </div>";    
        }
        $stmt->close(); 
        $conn->close();
    }
};

//Editar publicaiones
function editPublications($id){
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
            header("Location: talks.php");
        }else{
            header("Location: talks.php");
        }
    
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
    header("Location: talks.php");
}

$createPublications = function(){
    if((!empty($_SESSION["id"]))){
        if(isset($_POST["idcreatepublication"])){
            $idCreate= $_POST["idcreatepublication"];
            $conn = conectar();
            $stmt = $conn->prepare("SELECT usuarios.id, usuarios.nombre_usuario, fotos.enlace FROM usuarios LEFT JOIN fotos ON fotos.id_usuario = usuarios.id WHERE usuarios.id = ?;");
            $stmt->bind_param("i", $idCreate); 
            $stmt->execute(); 
            $stmt->bind_result($id, $nombre, $enlace); 
            $stmt->fetch(); 
            $varphoto=null;
            if  (!isset($enlace)){
                $varphoto="<span class=\"material-symbols-sharp\">person</span>";
            }else{
                $varphoto="<img id=\"imgpub\" src=\"$enlace\" alt=\"user_photo\">";
            }
            echo " <div id=\"create\">
                        <div id=\"descrcrtpub\">
                            <div id=\"phpcrttxt\">
                                $varphoto
                                <p id=\"nmbcrtpub\">What do you want to say?:</p>
                            </div>
                            <div class=\"icons\">
                                
                            </div>
                        </div>
                        <div id=\"conttext\">
                            <form method=\"post\" id=\"pubcrtForm\" >
                                <textarea name=\"crtpublication\" id=\"idcrtpublication\"></textarea>
                                <button type=\"submit\" class=\"btnpbl\" name=\"cmmts\" id=\"sendpubbtn\"><span class=\"material-symbols-sharp\">send</span></button>
                                <a href=\"talks.php\" class=\"btnpbl\"id=\"backpubbtn\"><span class=\"material-symbols-sharp\">close</span></a>   
                            </form>
                            
                        </div>
                    </div>
            </br>"; 
           
        }
    }
};
function publicate($id){
    if(isset($_POST["crtpublication"])){
        $textPublication = $_POST["crtpublication"];
        if(!empty($textPublication)){
            $conn = conectar();    
            $stmt = $conn->prepare("INSERT INTO publicaciones (id_usuario, contenido) values(?,?)");
            $stmt->bind_param("is",$id, $textPublication);
            $stmt->execute(); 
            $stmt->close(); 
            $conn->close();
            header("Location: talks.php");
        }
    }
}

?>