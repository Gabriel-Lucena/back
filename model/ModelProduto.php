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


        $extensao = pathinfo($this->_fotografia["name"], PATHINFO_EXTENSION);
        $novoNomeArquivo = md5(microtime()) . ".$extensao";
        move_uploaded_file($_FILES["fotografia"]["tmp_name"], "../img/$novoNomeArquivo");

        $stm = $this->_conexao->prepare($sql);

        $stm->bindValue(1, $this->_nome);
        $stm->bindValue(2, $this->_valor);
        $stm->bindValue(3, $this->_descricao);
        $stm->bindValue(4, $this->_idCategoria);
        $stm->bindValue(5, $novoNomeArquivo);

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

    public function update()
    {

        $this->_idProduto = $_POST["idProduto"];
        $this->_idCategoria = $_POST["idCategoria"];
        $this->_nome = $_POST["nome"];
        $this->_valor = $_POST["valor"];
        $this->_descricao = $_POST["descricao"];
        $this->_fotografia = $_FILES["fotografia"];

        // Relativo a imagem

        $sql = "SELECT fotografia FROM tblProduto WHERE idProduto = ?";
        $stm = $this->_conexao->prepare($sql);
        $stm->bindValue(1, $this->_idProduto);

        $stm->execute();

        $nomeFoto = $stm->fetchAll(\PDO::FETCH_ASSOC);

        // Exclusão da imagem antiga

        if ($nomeFoto[0]["fotografia"] != null) {

            unlink("../img/" . $nomeFoto[0]["fotografia"]);
        }

        unlink("../img/" . $nomeFoto[0]["fotografia"]);

        $extensao = pathinfo($this->_fotografia["name"], PATHINFO_EXTENSION);
        $novoNomeArquivo = md5(microtime()) . ".$extensao";
        move_uploaded_file($_FILES["fotografia"]["tmp_name"], "../img/$novoNomeArquivo");

        $sql = "UPDATE tblProduto SET
            nome = ?,
            valor = ?,
            descricao = ?,
            idCategoria = ?,
            fotografia = ?
            WHERE idProduto = ?";

        $stm = $this->_conexao->prepare($sql);

        $stm->bindValue(1, $this->_nome);
        $stm->bindValue(2, $this->_valor);
        $stm->bindValue(3, $this->_descricao);
        $stm->bindValue(4, $this->_idCategoria);
        $stm->bindValue(5, $novoNomeArquivo);
        $stm->bindValue(6, $this->_idProduto);

        if ($stm->execute()) {

            return array(
                "Dados alterados com sucesso!", $this->_nome, $this->_valor, $this->_idCategoria,
                $this->_descricao, $this->_idCategoria, $this->_fotografia["name"]
            );
        } else {

            return array(
                "Ocorreu algum erro.", $this->_nome, $this->_valor,
                $this->_idCategoria, $this->_descricao, $this->_idCategoria, $this->_fotografia["name"]
            );
        }
    }
}
