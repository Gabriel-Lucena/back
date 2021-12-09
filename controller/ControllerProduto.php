<?php

class ControllerProduto
{

    private $_method;
    private $_modelProduto;
    private $_idProduto;

    public function __construct($model)
    {
        $this->_modelProduto = $model;
        $this->_method = $_SERVER["REQUEST_METHOD"];

        $json = file_get_contents("php://input");
        $dadosProduto = json_decode($json);

        $this->_idProduto = $dadosProduto->idProduto ?? null;
    }

    function router()
    {

        switch ($this->_method) {
            case 'GET':

                if (isset($this->_idProduto)) {

                    return $this->_modelProduto->findById();
                }

                return $this->_modelProduto->findAll();

                break;

            case 'POST':

                if (isset($_POST["idProduto"])) {
                    
                    return $this->_modelProduto->update();
                }
                
                return $this->_modelProduto->create();

                break;

            case 'DELETE':

                return $this->_modelProduto->delete();

                break;

            case 'PUT':

                return $this->_modelProduto->update();

                break;
            default:
                # code...
                break;
        }
    }
}
