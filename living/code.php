<?php
function codigoRecuperacion() {
    $values = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $codigo = '';
    for ($i = 0; $i < 6; $i++) {
        $j = rand(0, strlen($values) - 1);
        $codigo .= $values[$j];
    }
    return $codigo;
}

?>