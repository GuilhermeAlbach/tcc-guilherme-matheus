<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class Cliente Extends Controller
{
    public function index()
    {
        echo $this->template->twig->render('cliente/listagem.html.twig');
    }

    public function formCadastrar()
    {
        $usuario_cliente = rand();
        $senha_cliente   = uniqid("");

        echo $this->template->twig->render('cliente/cadastrar.html.twig', compact('usuario_cliente','senha_cliente'));
    }

    public function formEditar($id_cliente)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM clientes WHERE id_cliente=:id_cliente";

        $query = $db->prepare($sql);
        $query->bindParam(":id_cliente", $id_cliente);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('cliente/editar.html.twig', compact('linha'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();
        if($_POST['TestaCPF'] = $_POST['cpf_cliente']){

        }

        $sql = "INSERT INTO clientes(nome_cliente,cidade_cliente,endereco_cliente,cpf_cliente,
                            telefone_cliente,sexo_cliente,datanascimento_cliente,observacao_cliente,
                            usuario_cliente,senha_cliente) 
                VALUES (:nome_cliente,:cidade_cliente,:endereco_cliente,:cpf_cliente,:telefone_cliente,
                        :sexo_cliente,:datanascimento_cliente,:observacao_cliente,:usuario_cliente,
                        :senha_cliente)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_cliente"          , $_POST['nome_cliente']);
        $query->bindParam(":cidade_cliente"        , $_POST['cidade_cliente']);
        $query->bindParam(":endereco_cliente"      , $_POST['endereco_cliente']);
        $query->bindParam(":cpf_cliente"           , $_POST['cpf_cliente']);
        $query->bindParam(":telefone_cliente"      , $_POST['telefone_cliente']);
        $query->bindParam(":sexo_cliente"          , $_POST['sexo_cliente']);
        $query->bindParam(":datanascimento_cliente", $_POST['datanascimento_cliente']);
        $query->bindParam(":observacao_cliente"    , $_POST['observacao_cliente']);
        $query->bindParam(":usuario_cliente"       , $_POST['usuario_cliente']);
        $query->bindParam(":senha_cliente"         , $_POST['senha_cliente']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Cliente cadastrado com sucesso');
        }else{
            $this->retornaErro('Erro ao inserir cliente');
        }
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        $sql = "UPDATE clientes SET nome_cliente=:nome_cliente, endereco_cliente=:endereco_cliente, 
                        cpf_cliente=:cpf_cliente, telefone_cliente=:telefone_cliente, senha_cliente=:senha_cliente,
                        sexo_cliente=:sexo_cliente, datanascimento_cliente=:datanascimento_cliente, 
                        observacao_cliente=:observacao_cliente, usuario_cliente=:usuario_cliente 
                WHERE id_cliente=:id_cliente";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_cliente"          , $_POST['nome_cliente']);
        $query->bindParam(":endereco_cliente"      , $_POST['endereco_cliente']);
        $query->bindParam(":cpf_cliente"           , $_POST['cpf_cliente']);
        $query->bindParam(":telefone_cliente"      , $_POST['telefone_cliente']);
        $query->bindParam(":sexo_cliente"          , $_POST['sexo_cliente']);
        $query->bindParam(":datanascimento_cliente", $_POST['datanascimento_cliente']);
        $query->bindParam(":observacao_cliente"    , $_POST['observacao_cliente']);
        $query->bindParam(":usuario_cliente"       , $_POST['usuario_cliente']);
        $query->bindParam(":senha_cliente"         , $_POST['senha_cliente']);
        $query->bindParam(":id_cliente"            , $_POST['id_cliente']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Cliente alterado com sucesso');
        }else{
            $this->retornaOK('Nenhum dado alterado');
        }
    }

    public function excluir(){
        $db = Conexao::connect();

        $sql = "DELETE FROM clientes WHERE id_cliente=:id_cliente";

        $query = $db->prepare($sql);
        $query->bindParam(":id_cliente", $_POST['id_cliente']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Cliente excluído com sucesso');
        }else{
            $this->retornaErro('Erro ao excluir cliente');
        }
    }


    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT * FROM clientes WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        nome_cliente LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
