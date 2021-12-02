<?php

class ModelProduto
{

    private $_conexao;
    private $_idProduto;
    private $_nome;
    private $_valor;
    private $_descricao;

    public function __construct($conexao)
    {
        // Permite receber dados .json através da requisição

        $json = file_get_contents("php://input");
        $dadosProduto = json_decode($json);

        // Recebimento dos dados do postman:

        $this->_idProduto = $dadosProduto->idProduto ?? null;
        $this->_nome = $dadosProduto->nome;
        $this->_valor = $dadosProduto->valor;
        $this->_descricao = $dadosProduto->descricao;
        $this->_conexao = $conexao;
    }

    public function findAll()
    {
        // Montagem do script SQL

        $sql = "SELECT * FROM tblProduto";

        $stm = $this->_conexao->prepare($sql);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function findById() {

        $sql = "SELECT * FROM tblProduto WHERE idProduto = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_idProduto);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }
}
