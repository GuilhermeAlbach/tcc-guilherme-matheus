<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class unidademedida Extends ControllerSeguro
{
    public function index()
    {
        echo $this->template->twig->render('unidademedida/listagem.html.twig');
    }

    public function formCadastrar()
    {
        echo $this->template->twig->render('unidademedida/cadastrar.html.twig');
    }

    public function formEditar($id_unidademedida)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM unidadesMedida WHERE id_unidademedida=:id_unidademedida";

        $query = $db->prepare($sql);
        $query->bindParam(":id_unidademedida", $id_unidademedida);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('unidademedida/editar.html.twig', compact('linha'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO unidadesMedida (unidademedida) VALUES (:unidademedida)";

        $query = $db->prepare($sql);
        $query->bindParam(":unidademedida", $_POST['unidademedida']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Unida de medida cadastrada com sucesso');
        }else{
            $this->retornaErro('Erro ao inserir os dados');
        }
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        $sql = "UPDATE unidadesMedida SET unidademedida=:unidademedida WHERE id_unidademedida=:id_unidademedida";

        $query = $db->prepare($sql);
        $query->bindParam(":unidademedida", $_POST['unidademedida']);
        $query->bindParam(":id_unidademedida", $_POST['id_unidademedida']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Unida de medida alterada com sucesso');
        }else{
            $this->retornaOK('Nenhum dado alterado');
        }
    }

    public function excluir(){
        try{
        $db = Conexao::connect();

        $sql = "DELETE FROM unidadesMedida WHERE id_unidademedida=:id_unidademedida";

        $query = $db->prepare($sql);
        $query->bindParam(":id_unidademedida", $_POST['id_unidademedida']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Excluído com sucesso');
        }else{
            $this->retornaErro('Erro ao excluir os dados');
        }
    }catch (\PDOException $exception){
        $this->retornaErro('Unidade de Medida não pode ser excluída.');
    }
}


    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT `id_unidademedida`, `unidademedida` FROM unidadesMedida WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        unidademedida LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
