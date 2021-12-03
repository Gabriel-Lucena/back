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
        $this->_nome = $dadosProduto->nome ?? null;
        $this->_valor = $dadosProduto->valor ?? null;
        $this->_descricao = $dadosProduto->descricao ?? null;
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

    public function findById()
    {

        $sql = "SELECT * FROM tblProduto WHERE idProduto = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_idProduto);
        $stm->execute();

        return $stm->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function create()
    {

        $sql = "INSERT INTO tblProduto (nome,
                                        valor, 
                                        descricao) VALUES (
                                        ?,
                                        ?,
                                        ?)";
        $stm = $this->_conexao->prepare($sql);

        $stm->bindValue(1, $this->_nome);
        $stm->bindValue(2, $this->_valor);
        $stm->bindValue(3, $this->_descricao);

        if ($stm->execute()) {
            return "Success";
        } else {
            return "Error";
        }
    }

    public function delete()
    {
        $sql = "DELETE FROM tblProduto WHERE idProduto = ?";

        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_idProduto);

        if ($stm->execute()) {

            return "Dados excluídos com sucesso!";
        } else {

            return "Ocorreu algum erro.";
        }
    }
}
