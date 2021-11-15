<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguroUsuario;

class Exame Extends ControllerSeguroUsuario
{
    public function index()
    {
        echo $this->template->twig->render('exame/listagem.html.twig');
    }

    public function formCadastrar()
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM bancadas ORDER BY bancada";
        $query_bancadas = $db->prepare($sql);
        $resultado_bancadas = $query_bancadas->execute();

        $bancadas = $query_bancadas->fetchAll();


        $sql = "SELECT * FROM metodos ORDER BY metodo";
        $query_metodos = $db->prepare($sql);
        $resultado_metodos = $query_metodos->execute();

        $metodos = $query_metodos->fetchAll();


        $sql = "SELECT * FROM materiais ORDER BY material";
        $query_materiais = $db->prepare($sql);
        $resultado_materiais = $query_materiais->execute();

        $materiais = $query_materiais->fetchAll();

        echo $this->template->twig->render('exame/cadastrar.html.twig', compact('bancadas','metodos','materiais'));
    }

    public function formEditar($id_exame)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM exames WHERE id_exame=:id_exame LIMIT 0, 1";

        $query = $db->prepare($sql);
        $query->bindParam(":id_exame", $id_exame);
        $resultado = $query->execute();

        $linha = $query->fetch();

        $sql = "SELECT * FROM bancadas ORDER BY bancada";
        $query_bancadas = $db->prepare($sql);
        $resultado_bancadas = $query_bancadas->execute();

        $bancadas = $query_bancadas->fetchAll();

        $sql = "SELECT * FROM metodos ORDER BY metodo";
        $query_metodos = $db->prepare($sql);
        $resultado_metodos = $query_metodos->execute();

        $metodos = $query_metodos->fetchAll();

        $sql = "SELECT * FROM materiais ORDER BY material";
        $query_materiais = $db->prepare($sql);
        $resultado_materiais = $query_materiais->execute();

        $materiais = $query_materiais->fetchAll();

        echo $this->template->twig->render('exame/editar.html.twig', compact('linha', 'bancadas','metodos','materiais'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO exames(nome_exame, sexo_exame, requisicao_exame, tempo_exame, 
        material_exame, metodo_exame, bancada_exame, descricao_exame, preco_exame) 
                VALUES (:nome_exame, :sexo_exame, :requisicao_exame, :tempo_exame,
        :material_exame, :metodo_exame, :bancada_exame, :descricao_exame, :preco_exame)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_exame"      , $_POST['nome_exame']);
        $query->bindParam(":sexo_exame"      , $_POST['sexo_exame']);
        $query->bindParam(":requisicao_exame", $_POST['requisicao_exame']);
        $query->bindParam(":tempo_exame"     , $_POST['tempo_exame']);
        $query->bindParam(":material_exame"  , $_POST['material_exame']);
        $query->bindParam(":metodo_exame"    , $_POST['metodo_exame']);
        $query->bindParam(":bancada_exame"   , $_POST['bancada_exame']);
        $query->bindParam(":descricao_exame" , $_POST['descricao_exame']);
        $query->bindParam(":preco_exame"     , $_POST['preco_exame']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Exame cadastrado com sucesso');
        }else{
            $this->retornaErro('Erro ao inserir exame');
        }
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        $sql = "UPDATE exames SET nome_exame=:nome_exame, sexo_exame=:sexo_exame, preco_exame=:preco_exame,
                    requisicao_exame=:requisicao_exame, tempo_exame=:tempo_exame,material_exame=:material_exame,
                    metodo_exame=:metodo_exame, bancada_exame=:bancada_exame, descricao_exame=:descricao_exame 
                WHERE id_exame=:id_exame";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_exame"      , $_POST['nome_exame']);
        $query->bindParam(":sexo_exame"      , $_POST['sexo_exame']);
        $query->bindParam(":requisicao_exame", $_POST['requisicao_exame']);
        $query->bindParam(":tempo_exame"     , $_POST['tempo_exame']);
        $query->bindParam(":material_exame"  , $_POST['material_exame']);
        $query->bindParam(":metodo_exame"    , $_POST['metodo_exame']);
        $query->bindParam(":bancada_exame"   , $_POST['bancada_exame']);
        $query->bindParam(":descricao_exame" , $_POST['descricao_exame']);
        $query->bindParam(":preco_exame"     , $_POST['preco_exame']);
        $query->bindParam(":id_exame"        , $_POST['id_exame']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Exame alterado com sucesso');
        }else{
            $this->retornaOK('Nenhum dado alterado');
        }
    }

    public function excluir(){
        try{
        $db = Conexao::connect();

        $sql = "DELETE FROM exames WHERE id_exame=:id_exame";

        $query = $db->prepare($sql);
        $query->bindParam(":id_exame", $_POST['id_exame']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Exame excluído com sucesso');
        }else{
            $this->retornaErro('Erro ao excluir Exame');
        }
    }catch (\PDOException $exception){
        $this->retornaErro('Exame não pode ser excluído.');
}
    }


    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT `id_exame`, `nome_exame`, `sexo_exame`, `requisicao_exame`, `tempo_exame`, `descricao_exame`, `preco_exame`, `material`, `bancada`, `metodo` FROM exames INNER JOIN materiais ON material_exame = id_material INNER JOIN bancadas ON bancada_exame = id_bancada INNER JOIN metodos ON metodo_exame = id_metodo WHERE 1";

        if ($busca!=''){
            $sql .= " and (
                id_exame LIKE '%{$busca}%' OR        
                nome_exame LIKE '%{$busca}%' OR
                metodo LIKE '%{$busca}%' OR
                material LIKE '%{$busca}%' OR
                bancada LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
