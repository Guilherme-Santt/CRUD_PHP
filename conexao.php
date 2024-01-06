<?php
$hostname = "localhost";
$bancodedados = "crud_clientes";
$usuario = "root";
$senha = "";

$mysqli = new mysqli($hostname, $usuario, $senha, $bancodedados);
if($mysqli->connect_errno){
    die("Falha na conexão com banco de dados");
};

function formatar_data($data){
    return implode('/', array_reverse(explode('-', $data)));
}

function formatar_telefone($telefone){
        $ddd    =  substr($telefone, 0 , 2);
        $parte1 =  substr($telefone, 2 , 4);;
        $parte2 =  substr($telefone, 7);
        return "($ddd) $parte1-$parte2";
}