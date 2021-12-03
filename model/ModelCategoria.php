<?php

class ModelCategoria
{

    private $_conexao;
    private $_idCategoria;
    private $_nome;

    public function __construct($conexao)
    {

        // Permite receber dados .json através da requisição

        $json = file_get_contents("php://input");
        $dadosCliente = json_decode($json);

        // Recebimento dos dados do postman:

        $this->_idCategoria = $dadosCliente->idCategoria ?? null;
        $this->_nome = $dadosCliente->_nome ?? null;
        $this->_conexao = $conexao;
    }

    public function findAll()
    {
        // Montagem do script SQL

        $sql = "SELECT * FROM tblCategoria";

        $stm = $this->_conexao->prepare($sql);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }
}
