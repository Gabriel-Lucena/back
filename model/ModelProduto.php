<?php

class ModelProduto
{

    private $_conexao;
    private $_idProduto;
    private $_nome;
    private $_preco;
    private $_descricao;

    public function __construct($conexao)
    {
        // Permite receber dados .json através da requisição

        $json = file_get_contents("php://input");
        $dadosProduto = json_decode($json);

        // Recebimento dos dados do postman:

        $this->_idProduto = $dadosProduto->idProduto ?? null;
        $this->_nome = $dadosProduto->nome;
        $this->_preco = $dadosProduto->preco;
        $this->_descricao = $dadosProduto->descricao;
        $this->_conexao = $conexao;
    }
}
