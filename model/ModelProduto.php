<?php

class ModelProduto
{

    private $_conexao;
    private $_idProduto;
    private $_nome;
    private $_valor;
    private $_descricao;
    private $_idCategoria;
    private $_fotografia;

    public function __construct($conexao)
    {

        // Permite receber dados .json através da requisição
        $json = file_get_contents("php://input");
        $dadosProduto = json_decode($json);

        $this->_idProduto = isset($dadosProduto->idProduto) ?
            $dadosProduto->idProduto :
            null;

        $this->_nome = isset($dadosProduto->nome) ? $dadosProduto->nome : (isset($_POST["nome"]) ?
            $_POST["nome"] :
            null);

        $this->_valor = isset($dadosProduto->valor) ? $dadosProduto->valor : (isset($_POST["valor"]) ?
            $_POST["valor"] :
            null);

        $this->_descricao = isset($dadosProduto->descricao) ? $dadosProduto->descricao : (isset($_POST["descricao"]) ?
            $_POST["descricao"] :
            null);

        $this->_idCategoria = isset($dadosProduto->idCategoria) ? $dadosProduto->idCategoria : (isset($_POST["idCategoria"]) ?
            $_POST["idCategoria"] :
            null);

        $this->_fotografia = isset($dadosProduto->fotografia) ? null : (isset($_FILES["fotografia"]) ?
            $_FILES["fotografia"] :
            null);

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
                                        descricao,
                                        idCategoria,
                                        fotografia) VALUES (
                                        ?,
                                        ?,
                                        ?,
                                        ?,
                                        ?)";


        // Recupera o nome do arquivo pelo post

        $nomeArquivo = $this->_fotografia["name"];

        // Divide tudo pelo .

        $dividido = explode(".", $nomeArquivo);

        // Recupera o que houver após o último ponto

        $extensao = "." . $dividido[count($dividido) - 1];

        $novoNomeArquivo = md5(microtime()) . "$extensao";
        move_uploaded_file($_FILES["fotografia"]["tmp_name"], "../img/$novoNomeArquivo");

        $stm = $this->_conexao->prepare($sql);

        $stm->bindValue(1, $this->_nome);
        $stm->bindValue(2, $this->_valor);
        $stm->bindValue(3, $this->_descricao);
        $stm->bindValue(4, $this->_idCategoria);
        $stm->bindValue(5, $novoNomeArquivo);

        if ($stm->execute()) {
            return array("Success", $this->_fotografia, $stm, $extensao, $novoNomeArquivo);
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

    public function update()
    {

        $sql = "UPDATE tblProduto SET
        nome = ?,
        valor = ?,
        descricao = ?,
        idCategoria = ?
        WHERE idProduto = ?";

        $stm = $this->_conexao->prepare($sql);

        $stm->bindValue(1, $this->_nome);
        $stm->bindValue(2, $this->_valor);
        $stm->bindValue(3, $this->_descricao);
        $stm->bindValue(4, $this->_idCategoria);
        $stm->bindValue(5, $this->_idProduto);

        if ($stm->execute()) {

            return "Dados alterados com sucesso!";
        } else {

            return "Ocorreu algum erro.";
        }
    }
}
