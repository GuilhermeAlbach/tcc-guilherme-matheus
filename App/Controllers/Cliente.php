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
        echo $this->template->twig->render('cliente/cadastrar.html.twig');
    }

    public function formEditar($id)
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

        $sql = "INSERT INTO clientes(nome_cliente,cidade_cliente,endereço_cliente,cpf_cliente,telefone_cliente,sexo_cliente,data_nascimento_cliente,observacao_cliente,usuario_cliente,senha_cliente) 
                             VALUES (:nome_cliente,:cidade_cliente,:endereço_cliente,:cpf_cliente,:telefone_cliente,:sexo_cliente,:data_nascimento_cliente,:observacao_cliente,:usuario_cliente,:senha_cliente)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_cliente"           , $_POST['nome_cliente']);
        $query->bindParam(":cidade_cliente"         , $_POST['cidade_cliente']);
        $query->bindParam(":endereço_cliente"       , $_POST['endereço_cliente']);
        $query->bindParam(":cpf_cliente"            , $_POST['cpf_cliente']);
        $query->bindParam(":telefone_cliente"       , $_POST['telefone_cliente']);
        $query->bindParam(":sexo_cliente"           , $_POST['sexo_cliente']);
        $query->bindParam(":data_nascimento_cliente", $_POST['data_nascimento_cliente']);
        $query->bindParam(":observacao_cliente"     , $_POST['observacao_cliente']);
        $query->bindParam(":usuario_cliente"        , $_POST['usuario_cliente']);
        $query->bindParam(":senha_cliente"          , $_POST['senha_cliente']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Cliente cadastrado com sucesso');
        }else{
            $this->retornaErro('Erro ao inserir os dados');
        }
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        $sql = "UPDATE clientes SET nome_cliente=:nome_cliente WHERE id_cliente=:id_cliente";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_cliente", $_POST['nome_cliente']);
        $query->bindParam(":id_cliente"  , $_POST['id_cliente']);
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
            $this->retornaOK('Excluído com sucesso');
        }else{
            $this->retornaErro('Erro ao excluir os dados');
        }
    }


    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT `id_cliente`, `nome_cliente` FROM clientes WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        nome_cliente LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
