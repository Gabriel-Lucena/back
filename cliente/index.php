<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Content-Type: application/json");

include('../Connection.php');
include('../model/ModelCliente.php');
include('../controller/ControllerCliente.php');

$conexao = new Connection();
$model = new ModelCliente($conexao->returnConnection());
$controller = new ControllerCliente($model);

$dados = $controller->router();

echo json_encode(array("status" => "Success", "data" => $dados));