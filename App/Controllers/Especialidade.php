<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class especialidade Extends Controller
{
    public function index()
    {
        echo $this->template->twig->render('especialidade/listagem.html.twig');
    }

    public function formCadastrar()
    {
        echo $this->template->twig->render('especialidade/cadastrar.html.twig');
    }

    public function formEditar($id_especialidade)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM especialidades WHERE id_especialidade=:id_especialidade";

        $query = $db->prepare($sql);
        $query->bindParam(":id_especialidade", $id_especialidade);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('especialidade/editar.html.twig', compact('linha'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO especialidades (especialidade) VALUES (:especialidade)";

        $query = $db->prepare($sql);
        $query->bindParam(":especialidade", $_POST['especialidade']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Especialidade cadastrada com sucesso');
        }else{
            $this->retornaErro('Erro ao inserir especialidade');
        }
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        $sql = "UPDATE especialidades SET especialidade=:especialidade WHERE id_especialidade=:id_especialidade";

        $query = $db->prepare($sql);
        $query->bindParam(":especialidade", $_POST['especialidade']);
        $query->bindParam(":id_especialidade", $_POST['id_especialidade']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Especialidade alterada com sucesso');
        }else{
            $this->retornaOK('Nenhum dado alterado');
        }
    }

    public function excluir(){
        try {
        $db = Conexao::connect();

        $sql = "DELETE FROM especialidades WHERE id_especialidade=:id_especialidade";

        $query = $db->prepare($sql);
        $query->bindParam(":id_especialidade", $_POST['id_especialidade']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Especialidade excluída com sucesso');
        }else {
            $this->retornaErro('Erro ao excluir especialidade');
        }
    }catch (\PDOException $exception){
        $this->retornaErro('Tipo não pode ser excluído.');
}
    }


    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT `id_especialidade`, `especialidade` FROM especialidades WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        especialidade LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
