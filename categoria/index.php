<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: application/json");

include('../Connection.php');
include('../model/ModelCategoria.php');
include('../controller/ControllerCategoria.php');

$conexao = new Connection();
$model = new ModelCategoria($conexao->returnConnection());
$controller = new ControllerCategoria($model);

$dados = $controller->router();

echo json_encode(array("status" => "Success", "data" => $dados));