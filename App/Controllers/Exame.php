<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class Exame Extends Controller
{
    public function index()
    {
        echo $this->template->twig->render('exame/listagem.html.twig');
    }

    public function formCadastrar()
    {
        echo $this->template->twig->render('exame/cadastrar.html.twig');
    }

    public function formEditar($id_exame)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM exames WHERE id_exame=:id_exame";

        $query = $db->prepare($sql);
        $query->bindParam(":id_exame", $id_exame);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('exame/editar.html.twig', compact('linha'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO exames(nome_exame, sexo_exame, requisicao_exame, tempo_exame, 
        undidade_exame, material_exame, metodo_exame, bancada_exame, descricao_exame) 
                VALUES (:nome_exame, :sexo_exame, :requisicao_exame, :tempo_exame, 
        :unidademedida_exame, :material_exame, :metodo_exame, :bancada_exame, :descricao_exame)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_exame"         , $_POST['nome_exame']);
        $query->bindParam(":sexo_exame"         , $_POST['sexo_exame']);
        $query->bindParam(":requisicao_exame"   , $_POST['requisicao_exame']);
        $query->bindParam(":tempo_exame"        , $_POST['tempo_exame']);
        $query->bindParam(":unidademedida_exame", $_POST['unidademedida_exame']);
        $query->bindParam(":material_exame"     , $_POST['material_exame']);
        $query->bindParam(":metodo_exame"       , $_POST['metodo_exame']);
        $query->bindParam(":bancada_exame"      , $_POST['bancada_exame']);
        $query->bindParam(":descricao_exame"    , $_POST['descricao_exame']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Exame cadastrado com sucesso');
        }else{
            $this->retornaErro('Erro ao inserir os dados');
        }
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        $sql = "UPDATE exames SET nome_exame=:nome_exame,sexo_exame=:sexo_exame,
                    requisicao_exame=:requisicao_exame,tempo_exame=:tempo_exame,
                    unidademedida_exame=:unidademedida_exame,material_exame=:material_exame,
                    metodo_exame=:metodo_exame,bancada_exame=:bancada_exame,descricao_exame=:descricao_exame 
                WHERE id_exame=:id_exame";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_exame"         , $_POST['nome_exame']);
        $query->bindParam(":sexo_exame"         , $_POST['sexo_exame']);
        $query->bindParam(":requisicao_exame"   , $_POST['requisicao_exame']);
        $query->bindParam(":tempo_exame"        , $_POST['tempo_exame']);
        $query->bindParam(":unidademedida_exame", $_POST['unidademedida_exame']);
        $query->bindParam(":material_exame"     , $_POST['material_exame']);
        $query->bindParam(":metodo_exame"       , $_POST['metodo_exame']);
        $query->bindParam(":bancada_exame"      , $_POST['bancada_exame']);
        $query->bindParam(":descricao_exame"    , $_POST['descricao_exame']);
        $query->bindParam(":id_exame"           , $_POST['id_exame']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Exame alterado com sucesso');
        }else{
            $this->retornaOK('Nenhum dado alterado');
        }
    }

    public function excluir(){
        $db = Conexao::connect();

        $sql = "DELETE FROM exames WHERE id_exame=:id_exame";

        $query = $db->prepare($sql);
        $query->bindParam(":id_exame", $_POST['id_exame']);
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
        $sql = "SELECT `id_exame`, `nome_exame` FROM exames WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        nome_exame LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
