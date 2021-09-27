<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class Bancada Extends ControllerSeguro
{
    public function index()
    {
        echo $this->template->twig->render('bancada/listagem.html.twig');
    }

    public function formCadastrar()
    {
        echo $this->template->twig->render('bancada/cadastrar.html.twig');
    }

    public function formEditar($id_bancada)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM bancadas WHERE id_bancada=:id_bancada";

        $query = $db->prepare($sql);
        $query->bindParam(":id_bancada", $id_bancada);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('bancada/editar.html.twig', compact('linha'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO bancadas (bancada) VALUES (:bancada)";

        $query = $db->prepare($sql);
        $query->bindParam(":bancada", $_POST['bancada']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Bancada cadastrada com sucesso');
        }else{
            $this->retornaErro('Erro ao inserir os dados');
        }
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        $sql = "UPDATE bancadas SET bancada=:bancada WHERE id_bancada=:id_bancada";

        $query = $db->prepare($sql);
        $query->bindParam(":bancada", $_POST['bancada']);
        $query->bindParam(":id_bancada", $_POST['id_bancada']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Bancada alterada com sucesso');
        }else{
            $this->retornaOK('Nenhum dado alterado');
        }
    }

    public function excluir(){
        try{
            $db = Conexao::connect();

            $sql = "DELETE FROM bancadas WHERE id_bancada=:id_bancada";

            $query = $db->prepare($sql);
            $query->bindParam(":id_bancada", $_POST['id_bancada']);
            $query->execute();

            if ($query->rowCount()==1) {
                $this->retornaOK('Bancada excluída com sucesso');
            }else{
                $this->retornaErro('Erro ao excluir Bancada');   
        }
        }catch (\PDOException $exception){
            $this->retornaErro('Bancada não pode ser excluída.');
        }
    }


    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT `id_bancada`, `bancada` FROM bancadas WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        bancada LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
