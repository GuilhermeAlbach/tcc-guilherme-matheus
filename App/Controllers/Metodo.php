<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguroUsuario;

class metodo Extends ControllerSeguroUsuario
{
    public function index()
    {
        echo $this->template->twig->render('metodo/listagem.html.twig');
    }

    public function formCadastrar()
    {
        echo $this->template->twig->render('metodo/cadastrar.html.twig');
    }

    public function formEditar($id_metodo)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM metodos WHERE id_metodo=:id_metodo";

        $query = $db->prepare($sql);
        $query->bindParam(":id_metodo", $id_metodo);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('metodo/editar.html.twig', compact('linha'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO metodos (metodo) VALUES (:metodo)";

        $query = $db->prepare($sql);
        $query->bindParam(":metodo", $_POST['metodo']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Método cadastrado com sucesso');
        }else{
            $this->retornaErro('Erro ao inserir os dados');
        }
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        $sql = "UPDATE metodos SET metodo=:metodo WHERE id_metodo=:id_metodo";

        $query = $db->prepare($sql);
        $query->bindParam(":metodo", $_POST['metodo']);
        $query->bindParam(":id_metodo", $_POST['id_metodo']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Método alterado com sucesso');
        }else{
            $this->retornaOK('Nenhum dado alterado');
        }
    }

    public function excluir(){
        try{
            $db = Conexao::connect();

            $sql = "DELETE FROM metodos WHERE id_metodo=:id_metodo";

            $query = $db->prepare($sql);
            $query->bindParam(":id_metodo", $_POST['id_metodo']);
            $query->execute();

            if ($query->rowCount()==1) {
                $this->retornaOK('Método excluído com sucesso');
            }else{
                $this->retornaErro('Erro ao excluir os dados');
            }
        }catch (\PDOException $exception){
            $this->retornaErro('Método não pode ser excluído.');
        }
        }


    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT `id_metodo`, `metodo` FROM metodos WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        metodo LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
