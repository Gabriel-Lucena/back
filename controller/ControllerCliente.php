<?php

class ControllerCliente
{

    private $_method;
    private $_modelCliente;
    private $_idCliente;


    public function __construct($model)
    {

        $this->_modelCliente = $model;
        $this->_method = $_SERVER["REQUEST_METHOD"];

        $json = file_get_contents("php://input");

        $dadosCliente = json_decode($json);

        $this->_idCliente = $dadosCliente->idCliente ?? null;
    }

    function router()
    {

        switch ($this->_method) {
            case 'GET':

                if (isset($this->_idCliente)) {

                    return $this->_modelCliente->findById();
                }

                return $this->_modelCliente->findAll();

                break;

            case 'POST':

                return $this->_modelCliente->create();

                break;

            case 'DELETE':

                return $this->_modelCliente->delete();

                break;

            case 'PUT':

                return $this->_modelCliente->update();

                break;
            default:

                break;
        }
    }
}
