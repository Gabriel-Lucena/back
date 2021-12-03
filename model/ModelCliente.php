<?php

class ModelCliente
{

    private $_conexao;
    private $_idCliente;
    private $_nome;
    private $_sobrenome;
    private $_email;
    private $_dataNascimento;
    private $_cpf;
    private $_telefone;

    public function __construct($conexao)
    {

        // Permite receber dados .json através da requisição

        $json = file_get_contents("php://input");
        $dadosCliente = json_decode($json);

        // Recebimento dos dados do postman:

        $this->_idCliente = $dadosCliente->idCliente ?? null;
        $this->_nome = $dadosCliente->nome ?? null;
        $this->_sobrenome = $dadosCliente->sobrenome ?? null;
        $this->_email = $dadosCliente->email ?? null;
        $this->_dataNascimento = $dadosCliente->dataNascimento ?? null;
        $this->_cpf = $dadosCliente->cpf ?? null;
        $this->_telefone = $dadosCliente->telefone ?? null;
        $this->_conexao = $conexao;
    }

    public function findAll()
    {
        // Montagem do script SQL

        $sql = "SELECT * FROM tblCliente";

        $stm = $this->_conexao->prepare($sql);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findById()
    {
        $sql = "SELECT * FROM tblCliente WHERE idCliente = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_idCliente);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }
}
