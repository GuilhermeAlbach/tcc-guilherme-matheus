<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class material Extends ControllerSeguro
{
    public function index()
    {
        echo $this->template->twig->render('material/listagem.html.twig');
    }

    public function formCadastrar()
    {
        echo $this->template->twig->render('material/cadastrar.html.twig');
    }

    public function formEditar($id_material)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM materiais WHERE id_material=:id_material";

        $query = $db->prepare($sql);
        $query->bindParam(":id_material", $id_material);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('material/editar.html.twig', compact('linha'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO materiais (material) VALUES (:material)";

        $query = $db->prepare($sql);
        $query->bindParam(":material", $_POST['material']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Material cadastrado com sucesso');
        }else{
            $this->retornaErro('Erro ao inserir material');
        }
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        $sql = "UPDATE materiais SET material=:material WHERE id_material=:id_material";

        $query = $db->prepare($sql);
        $query->bindParam(":material", $_POST['material']);
        $query->bindParam(":id_material", $_POST['id_material']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Material alterado com sucesso');
        }else{
            $this->retornaOK('Nenhum dado alterado');
        }
    }

    public function excluir(){
        try{
        $db = Conexao::connect();

        $sql = "DELETE FROM materiais WHERE id_material=:id_material";

        $query = $db->prepare($sql);
        $query->bindParam(":id_material", $_POST['id_material']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Material excluído com sucesso');
        }else{
            $this->retornaErro('Erro ao excluir material');
        }
    }catch (\PDOException $exception){
        $this->retornaErro("Este Material não pode ser excluído");
    }
}


    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT `id_material`, `material` FROM materiais WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        material LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
