<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class Guia Extends Controller
{
    public function index()
    {
        echo $this->template->twig->render('guia/listagem.html.twig');
    }

    public function formCadastrar()
    {
        echo $this->template->twig->render('guia/cadastrar.html.twig');
    }

    public function formEditar($id)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM guias WHERE id_guia=:id_guia";

        $query = $db->prepare($sql);
        $query->bindParam(":id_guia", $id_guia);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('guia/editar.html.twig', compact('linha'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO guias (data_guia, prazo_guia, cliente_guia, medico_guia, 
                            convenio_guia, codigo_guia, senha_guia) 
                VALUES (:data_guia, :prazo_guia, :cliente_guia, :medico_guia, 
                            :convenio_guia, :codigo_guia, :senha_guia)";

        $query = $db->prepare($sql);
        $query->bindParam(":data_guia"    , $_POST['data_guia']);
        $query->bindParam(":prazo_guia"   , $_POST['prazo_guia']);
        $query->bindParam(":cliente_guia" , $_POST['cliente_guia']);
        $query->bindParam(":medico_guia"  , $_POST['medico_guia']);
        $query->bindParam(":convenio_guia", $_POST['convenio_guia']);
        $query->bindParam(":codigo_guia"  , $_POST['codigo_guia']);
        $query->bindParam(":senha_guia"   , $_POST['senha_guia']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Guia cadastrada com sucesso');
        }else{
            $this->retornaErro('Erro ao inserir os dados');
        }
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        $sql = "UPDATE guias SET data_guia=:data_guia, prazo_guia=:prazo_guia, cliente_guia=:cliente_guia, medico_guia=:medico_guia, convenio_guia=:convenio_guia, codigo_guia=:codigo_guia, senha_guia=:senha_guia
                WHERE id_guia=:id_guia";

        $query = $db->prepare($sql);
        $query->bindParam(":data_guia"    , $_POST['data_guia']);
        $query->bindParam(":prazo_guia"   , $_POST['prazo_guia']);
        $query->bindParam(":cliente_guia" , $_POST['cliente_guia']);
        $query->bindParam(":medico_guia"  , $_POST['medico_guia']);
        $query->bindParam(":convenio_guia", $_POST['convenio_guia']);
        $query->bindParam(":codigo_guia"  , $_POST['codigo_guia']);
        $query->bindParam(":senha_guia"   , $_POST['senha_guia']);
        $query->bindParam(":id_guia"      , $_POST['id_guia']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Guia alterada com sucesso');
        }else{
            $this->retornaOK('Nenhum dado alterado');
        }
    }

    public function excluir(){
        $db = Conexao::connect();

        $sql = "DELETE FROM guias WHERE id_guia=:id_guia";

        $query = $db->prepare($sql);
        $query->bindParam(":id_guia", $_POST['id_guia']);
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
        $sql = "SELECT `id_guia`,'data_guia' FROM guias WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        data_guia LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
