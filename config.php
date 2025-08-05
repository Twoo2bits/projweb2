<?php

$servidor = "localhost"; 
$usuario_bd = "root";    
$senha_bd = "";         
$banco_de_dados = "techshop"; 


$conn = new mysqli($servidor, $usuario_bd, $senha_bd, $banco_de_dados);


if ($conn->connect_error) {
    die("Falha na conexão com o banco de dados '" . $banco_de_dados . "': " . $conn->connect_error);
}


$conn->set_charset("utf8");

?>
