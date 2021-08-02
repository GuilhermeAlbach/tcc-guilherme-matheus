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
        $db = Conexao::connect();
        
        $sql = "SELECT * FROM unidadesmedida ORDER BY unidademedida";
        $query_unidadesmedida = $db->prepare($sql);
        $resultado_unidadesmedida = $query_unidadesmedida->execute();

        $unidadesmedida = $query_unidadesmedida->fetchAll();
       

        $sql = "SELECT * FROM exames ORDER BY nome_exame";
        $query_exames = $db->prepare($sql);
        $resultado_exames = $query_exames->execute();

        $exames = $query_exames->fetchAll();

        echo $this->template->twig->render('valorreferencia/cadastrar.html.twig', compact('unidadesmedida','exames'));
    }

    public function formEditar($id_valorreferencia)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM valoresreferencia WHERE id_valorreferencia=:id_valorreferencia";

        $query = $db->prepare($sql);
        $query->bindParam(":id_valorreferencia", $id_valorreferencia);
        $resultado = $query->execute();

        $linha = $query->fetch();

        $sql = "SELECT * FROM unidadesmedida ORDER BY unidademedida";
        $query_unidadesmedida = $db->prepare($sql);
        $resultado_unidadesmedida = $query_unidadesmedida->execute();

        $unidadesmedida = $query_unidadesmedida->fetchAll();
      
        $sql = "SELECT * FROM exames ORDER BY nome_exame";
        $query_exames = $db->prepare($sql);
        $resultado_exames = $query_exames->execute();

        $exames = $query_exames->fetchAll();

        echo $this->template->twig->render('valorreferencia/editar.html.twig', compact('linha','unidadesmedida','exames'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO valoresreferencia(exame_valorreferencia,valorreferencia,
                            valorreferencia_min,valorreferencia_max,idade_min,idade_max,sexo,unidademedida_valorreferencia) 
                    VALUES (:exame_valorreferencia,:valorreferencia,:valorreferencia_min,
                            :valorreferencia_max,:idade_min,:idade_max,:sexo,
                            :unidademedida_valorreferencia)";

        $query = $db->prepare($sql);
        $query->bindParam(":exame_valorreferencia"        , $_POST['exame_valorreferencia']);
        $query->bindParam(":unidademedida_valorreferencia", $_POST['unidademedida_valorreferencia']);
        $query->bindParam(":valorreferencia"              , $_POST['valorreferencia']);
        $query->bindParam(":valorreferencia_min"          , $_POST['valorreferencia_min']);
        $query->bindParam(":valorreferencia_max"          , $_POST['valorreferencia_max']);
        $query->bindParam(":idade_min"                    , $_POST['idade_min']);
        $query->bindParam(":idade_max"                    , $_POST['idade_max']);
        $query->bindParam(":sexo"                         , $_POST['sexo']);
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
                        unidademedida_valorreferencia=:unidademedida_valorreferencia,
                        valorreferencia=:valorreferencia, valorreferencia_min=:valorreferencia_min, 
                        valorreferencia_max=:valorreferencia_max, idade_min=:idade_min, idade_max=:idade_max, sexo=:sexo 
                WHERE id_valorreferencia=:id_valorreferencia";

        $query = $db->prepare($sql);
        $query->bindParam(":exame_valorreferencia"        , $_POST['exame_valorreferencia']);
        $query->bindParam(":unidademedida_valorreferencia", $_POST['unidademedida_valorreferencia']);
        $query->bindParam(":valorreferencia"              , $_POST['valorreferencia']);
        $query->bindParam(":valorreferencia_min"          , $_POST['valorreferencia_min']);
        $query->bindParam(":valorreferencia_max"          , $_POST['valorreferencia_max']);
        $query->bindParam(":idade_min"                    , $_POST['idade_min']);
        $query->bindParam(":idade_max"                    , $_POST['idade_max']);
        $query->bindParam(":sexo"                         , $_POST['sexo']);
        $query->bindParam(":id_valorreferencia"           , $_POST['id_valorreferencia']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Valor de referência alterado com sucesso');
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
        $sql = "SELECT `id_valorreferencia`, `exame_valorreferencia`, `unidademedida_valorreferencia`, `valorreferencia`, `valorreferencia_min`, `valorreferencia_max`, `idade_min`, `idade_max`, `sexo` FROM valoresreferencia INNER JOIN unidadesmedida ON unidademedida_valorreferencia = id_unidademedida INNER JOIN exames ON exame_valorreferencia = id_exame WHERE 1 ";
        if ($busca!=''){
            $sql .= " and (
                        exame_valorreferencia LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
