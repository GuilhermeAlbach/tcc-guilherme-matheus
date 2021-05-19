<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class Convenio Extends Controller
{
    public function index()
    {
        echo $this->template->twig->render('convenio/listagem.html.twig');
    }

    public function formCadastrar()
    {
        echo $this->template->twig->render('convenio/cadastrar.html.twig');
    }

    public function formEditar($id_convenio)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM convenios WHERE id_convenio=:id_convenio";

        $query = $db->prepare($sql);
        $query->bindParam(":id_convenio", $id_convenio);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('convenio/editar.html.twig', compact('linha'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO convenios (nome_convenio, cnpj_convenio, responsavel_convenio, telefone_convenio) 
                VALUES (:nome_convenio, :cnpj_convenio, :responsavel_convenio, :telefone_convenio)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_convenio"       , $_POST['nome_convenio']);
        $query->bindParam(":cnpj_convenio"       , $_POST['cnpj_convenio']);
        $query->bindParam(":responsavel_convenio", $_POST['responsavel_convenio']);
        $query->bindParam(":telefone_convenio"   , $_POST['telefone_convenio']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Tipo cadastrado com sucesso');
        }else{
            $this->retornaErro('Erro ao inserir os dados');
        }
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        $sql = "UPDATE convenios SET nome_convenio=:nome_convenio,cnpj_convenio=:cnpj_convenio,responsavel_convenio=:responsavel_convenio,telefone_convenio=:telefone_convenio  WHERE id_convenio=:id_convenio";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_convenio"       , $_POST['nome_convenio']);
        $query->bindParam(":cnpj_convenio"       , $_POST['cnpj_convenio']);
        $query->bindParam(":responsavel_convenio", $_POST['responsavel_convenio']);
        $query->bindParam(":telefone_convenio"   , $_POST['telefone_convenio']);
        $query->bindParam(":id_convenio", $_POST['id_convenio']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('convenio alterado com sucesso');
        }else{
            $this->retornaOK('Nenhum dado alterado');
        }
    }

    public function excluir(){
        $db = Conexao::connect();

        $sql = "DELETE FROM convenios WHERE id_convenio=:id_convenio";

        $query = $db->prepare($sql);
        $query->bindParam(":id_convenio", $_POST['id_convenio']);
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
        $sql = "SELECT `id_convenio`, `nome_convenio` FROM convenios WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        nome_convenio LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
