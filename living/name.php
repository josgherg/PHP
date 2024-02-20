<?php


function validateName($str) {
    if (empty($str)){
        return(false);
    }else{
        $matches = null;
        return (1 === preg_match('/^[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?(( |\-)[a-zA-ZÀ-ÖØ-öø-ÿ]+\.?)*/', $str, $matches));
    }
    
}


?>