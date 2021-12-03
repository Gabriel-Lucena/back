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
        $this->_nome = $dadosCliente->nome ?? null;
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

    public function findById()
    {
        $sql = "SELECT * FROM tblCategoria WHERE idCategoria = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_idCategoria);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create()
    {

        $sql = "INSERT INTO tblCategoria (nome) VALUES (?)";

        $stm = $this->_conexao->prepare($sql);

        $stm->bindValue(1, $this->_nome);

        if ($stm->execute()) {
            return "Success";
        } else {
            return "Error";
        }
    }
}
