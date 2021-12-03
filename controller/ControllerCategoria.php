<?php

class ControllerCategoria
{

    private $_method;
    private $_modelCategoria;
    private $_idCategoria;

    public function __construct($model)
    {

        $this->_modelCategoria = $model;
        $this->_method = $_SERVER["REQUEST_METHOD"];

        $json = file_get_contents("php://input");

        $dadosCategoria = json_decode($json);

        $this->_idCategoria = $dadosCategoria->idCategoria ?? null;
    }

    function router()
    {
        switch ($this->_method) {
            case 'GET':

                if (isset($this->_idCategoria)) {

                    return $this->_modelCategoria->findById();
                }

                return $this->_modelCategoria->findAll();

                break;

            case 'POST':

                return $this->_modelCategoria->create();

                break;

            case 'DELETE':

                return $this->_modelCategoria->delete();

                break;


            case 'PUT':

                return $this->_modelCategoria->update();

                break;

            default:

                break;
        }
    }
}
