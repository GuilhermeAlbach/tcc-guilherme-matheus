<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class ValorReferencia Extends Controller
{
    public function index()
    {
        echo $this->template->twig->render('valorreferencia/listagem.html.twig');
    }

    public function formCadastrar()
    {
        echo $this->template->twig->render('valorreferencia/cadastrar.html.twig');
    }

    public function formEditar($id)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM valoresreferencia WHERE id_valorreferencia=:id_valorreferencia";

        $query = $db->prepare($sql);
        $query->bindParam(":id_valorreferencia", $id_valorreferencia);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('valorreferencia/editar.html.twig', compact('linha'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO valoresreferencia(exames_valorreferencia,valorreferencia,valorreferencia_min,valorreferencia_max,idade_min,idade_max,sexo) 
                             VALUES (:exames_valorreferencia,:valorreferencia,:valorreferencia_min,:valorreferencia_max,:idade_min,:idade_max,:sexo)";

        $query = $db->prepare($sql);
        $query->bindParam(":exames_valorreferencia", $_POST['exames_valorreferencia']);
        $query->bindParam(":valorreferencia"       , $_POST['valorreferencia']);
        $query->bindParam(":valorreferencia_min"   , $_POST['valorreferencia_min']);
        $query->bindParam(":valorreferencia_max"   , $_POST['valorreferencia_max']);
        $query->bindParam(":idade_min"             , $_POST['idade_min']);
        $query->bindParam(":idade_max"             , $_POST['idade_max']);
        $query->bindParam(":sexo"                  , $_POST['sexo']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Valor de Referência cadastrado com sucesso');
        }else{
            $this->retornaErro('Erro ao inserir os dados');
        }
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        $sql = "UPDATE valoresreferencia SET exame_valorreferencia=:exame_valorreferencia, 
        valorreferencia=:valorreferencia, valorreferencia_min=:valorreferencia_min, 
        valorreferencia_max=:valorreferencia_max, idade_min=:idade_min, idade_max=:idade_max, sexo=:sexo 
                WHERE id_valorreferencia=:id_valorreferencia";

        $query = $db->prepare($sql);
        $query->bindParam(":exame_valorreferencia", $_POST['exame_valorreferencia']);
        $query->bindParam(":valorreferencia"      , $_POST['valorreferencia']);
        $query->bindParam(":valorreferencia_min"  , $_POST['valorreferencia_min']);
        $query->bindParam(":valorreferencia_max"  , $_POST['valorreferencia_max']);
        $query->bindParam(":idade_min"            , $_POST['idade_min']);
        $query->bindParam(":idade_max"            , $_POST['idade_max']);
        $query->bindParam(":sexo"                 , $_POST['sexo']);
        $query->bindParam(":id_cliente"           , $_POST['id_cliente']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Cliente alterado com sucesso');
        }else{
            $this->retornaOK('Nenhum dado alterado');
        }
    }

    public function excluir(){
        $db = Conexao::connect();

        $sql = "DELETE FROM valoresreferencia WHERE id_valorreferencia=:id_valorreferencia";

        $query = $db->prepare($sql);
        $query->bindParam(":id_valorreferencia", $_POST['id_valorreferencia']);
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
        $sql = "SELECT `id_valorreferencia`, `exame_valorreferencia` FROM clientes WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        exame_valorreferencia LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
