<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class guia Extends Controller
{
    public function index()
    {
        echo $this->template->twig->render('guia/listagem.html.twig');
    }

    public function formCadastrar()
    {
        date_default_timezone_set('America/Sao_Paulo');

        $codigo_guia = rand();
        $senha_guia  = uniqid("");
        $data_guia   = date("Y-m-d H:i:s");

        $db = Conexao::connect();

        $sql = "SELECT * FROM medicos ORDER BY nome_medico";
        $query_medicos = $db->prepare($sql);
        $resultado_medicos = $query_medicos->execute();

        $medicos = $query_medicos->fetchAll();

        $sql = "SELECT * FROM clientes ORDER BY nome_cliente";
        $query_clientes = $db->prepare($sql);
        $resultado_clientes = $query_clientes->execute();

        $clientes = $query_clientes->fetchAll();

        $sql = "SELECT * FROM convenios ORDER BY nome_convenio";
        $query_convenios = $db->prepare($sql);
        $resultado_convenios = $query_convenios->execute();

        $convenios = $query_convenios->fetchAll();

        echo $this->template->twig->render('guia/cadastrar.html.twig', compact('medicos','clientes','convenios','codigo_guia','senha_guia','data_guia'));
    }

    public function formEditar($id_guia)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM guiasexames ORDER BY guia_guiaexame";
        $query2 = $db->prepare($sql);
        $resultado2 = $query2->execute();

        $linha2 = $query2->fetch();

        $sql = "SELECT `id_guia`, `data_guia`, `cliente_guia`, `medico_guia`, `convenio_guia`, `codigo_guia`, `senha_guia`,`precototal_guia`, `prazofinal_guia`, `nome_medico`, `nome_cliente`, `nome_convenio` FROM guias INNER JOIN medicos ON medico_guia = id_medico INNER JOIN clientes ON cliente_guia = id_cliente INNER JOIN convenios ON convenio_guia = id_convenio WHERE id_guia=:id_guia LIMIT 0, 1";
        $query = $db->prepare($sql);
        $query->bindParam(":id_guia", $id_guia);
        $resultado = $query->execute();

        $linha = $query->fetch();

        $sql = "SELECT * FROM medicos ORDER BY nome_medico";
        $query_medicos = $db->prepare($sql);
        $resultado_medicos = $query_medicos->execute();

        $medicos = $query_medicos->fetchAll();

        $sql = "SELECT * FROM clientes ORDER BY nome_cliente";
        $query_clientes = $db->prepare($sql);
        $resultado_clientes = $query_clientes->execute();

        $clientes = $query_clientes->fetchAll();

        $sql = "SELECT * FROM convenios ORDER BY nome_convenio";
        $query_convenios = $db->prepare($sql);
        $resultado_convenios = $query_convenios->execute();

        $convenios = $query_convenios->fetchAll();

        echo $this->template->twig->render('guia/editar.html.twig', compact('linha','linha2', 'medicos', 'clientes', 'convenios'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO guias (data_guia, cliente_guia, convenio_guia, medico_guia,
                            codigo_guia,senha_guia) 
                VALUES (:data_guia, :cliente_guia, :convenio_guia, :medico_guia, 
                        :codigo_guia, :senha_guia)";

        $query = $db->prepare($sql);
        $query->bindParam(":data_guia"    , $_POST['data_guia']);
        $query->bindParam(":cliente_guia" , $_POST['cliente_guia']);
        $query->bindParam(":convenio_guia", $_POST['convenio_guia']);
        $query->bindParam(":medico_guia"  , $_POST['medico_guia']);
        $query->bindParam(":codigo_guia"  , $_POST['codigo_guia']);
        $query->bindParam(":senha_guia"   , $_POST['senha_guia']);
        $query->execute();

        if ($query->rowCount()==1) {
            $retorno['status'] = 1;
            $retorno['mensagem'] = 'Guia cadastrada com sucesso';
            $retorno['id_guia'] = $db->lastInsertId();

            echo $this->jsonResponse($retorno);
        }else{
            $this->retornaErro('Erro ao inserir os dados');
        }
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        $sql = "UPDATE guias SET data_guia=:data_guia,medico_guia=:medico_guia,
                    cliente_guia=:cliente_guia, convenio_guia=:convenio_guia,codigo_guia=:codigo_guia,  
                    senha_guia=:senha_guia, prazofinal_guia=:prazofinal_guia, precototal_guia=:precototal_guia
                WHERE id_guia=:id_guia";

        $query = $db->prepare($sql);
        $query->bindParam(":data_guia"      , $_POST['data_guia']);
        $query->bindParam(":cliente_guia"   , $_POST['cliente_guia']);
        $query->bindParam(":convenio_guia"  , $_POST['convenio_guia']);
        $query->bindParam(":medico_guia"    , $_POST['medico_guia']);
        $query->bindParam(":codigo_guia"    , $_POST['codigo_guia']);
        $query->bindParam(":senha_guia"     , $_POST['senha_guia']);
        $query->bindParam(":precototal_guia", $_POST['precototal_guia']);
        $query->bindParam(":prazofinal_guia", $_POST['prazofinal_guia']);
        $query->bindParam(":id_guia"        , $_POST['id_guia']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('guia alterado com sucesso');
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
            $this->retornaOK('guia excluído com sucesso');
        }else{
            $this->retornaErro('Erro ao excluir guia');
        }
    
    }


    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT `id_guia`, `data_guia`, `cliente_guia`, `medico_guia`, `convenio_guia`, `codigo_guia`, `senha_guia`, `nome_medico`, `nome_cliente`, `nome_convenio` FROM guias INNER JOIN medicos ON medico_guia = id_medico INNER JOIN clientes ON cliente_guia = id_cliente INNER JOIN convenios ON convenio_guia = id_convenio WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        data_guia LIKE '%{$busca}%' OR
                        nome_medico LIKE '%{$busca}%' OR
                        nome_cliente LIKE '%{$busca}%' OR
                        nome_convenio LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }


    public function formCadastrarExame($id_guia)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM guiasexames ORDER BY guia_guiaexame";
        $query2 = $db->prepare($sql);
        $resultado2 = $query2->execute();

        $linha2 = $query2->fetch();

        $sql = "SELECT `id_guia`, `data_guia`, `cliente_guia`, `medico_guia`, `convenio_guia`, `codigo_guia`, `senha_guia`,`precototal_guia`, `prazofinal_guia`, `nome_medico`, `nome_cliente`, `nome_convenio` FROM guias INNER JOIN medicos ON medico_guia = id_medico INNER JOIN clientes ON cliente_guia = id_cliente INNER JOIN convenios ON convenio_guia = id_convenio WHERE id_guia=:id_guia LIMIT 0, 1";
        $query = $db->prepare($sql);
        $query->bindParam(":id_guia", $id_guia);
        $resultado = $query->execute();

        $linha = $query->fetch();

        $sql = "SELECT * FROM medicos ORDER BY nome_medico";
        $query_medicos = $db->prepare($sql);
        $resultado_medicos = $query_medicos->execute();

        $medicos = $query_medicos->fetchAll();

        $sql = "SELECT * FROM clientes ORDER BY nome_cliente";
        $query_clientes = $db->prepare($sql);
        $resultado_clientes = $query_clientes->execute();

        $clientes = $query_clientes->fetchAll();

        $sql = "SELECT * FROM convenios ORDER BY nome_convenio";
        $query_convenios = $db->prepare($sql);
        $resultado_convenios = $query_convenios->execute();

        $convenios = $query_convenios->fetchAll();

        $sql = "SELECT * FROM exames ORDER BY nome_exame";
        $query_exames = $db->prepare($sql);
        $resultado_exames = $query_exames->execute();

        $exames = $query_exames->fetchAll();

        echo $this->template->twig->render('guia/cadastrarExame.html.twig', compact('linha', 'linha2', 'medicos', 'clientes', 'convenios','exames'));
    }

    public function formEditarExame($id_guiaexame)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM guiasexames WHERE id_guiaexame=:id_guiaexame LIMIT 0, 1";

        $query = $db->prepare($sql);
        $query->bindParam(":id_guiaexame", $id_guiaexame);
        $resultado = $query->execute();

        $linha = $query->fetch();

        $sql = "SELECT * FROM exames ORDER BY nome_exame";
        $query_exames = $db->prepare($sql);
        $resultado_exames = $query_exames->execute();

        $exames = $query_exames->fetchAll();

        echo $this->template->twig->render('guia/editarExame.html.twig', compact('linha', 'exames'));
    }



    public function salvarCadastrarExame()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO guiasexames (exame_guiaexame, guia_guiaexame, preco_guiaexame, prazo_guiaexame)
                VALUES (:exame_guiaexame, :guia_guiaexame, :preco_guiaexame, :prazo_guiaexame)";

        $query = $db->prepare($sql);
        $query->bindParam(":guia_guiaexame" , $_POST['guia_guiaexame']);
        $query->bindParam(":preco_guiaexame", $_POST['preco_guiaexame']);
        $query->bindParam(":prazo_guiaexame", $_POST['prazo_guiaexame']);
        $query->bindParam(":exame_guiaexame", $_POST['exame_guiaexame']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Médico cadastrado com sucesso');
        }else{
            $this->retornaErro('Erro ao inserir os dados');
        }
    }

    public function salvarEditarExame()
    {
        $db = Conexao::connect();

        $sql = "UPDATE guiasexames SET guia_guiaexame=:guia_guiaexame,prazo_guiaexame=:prazo_guiaexame,
                        exame_guiaexame=:exame_guiaexame,preco_guiaexame=:preco_guiaexame 
                WHERE id_guiaexame=:id_guiaexame";

        $query = $db->prepare($sql);
        $query->bindParam(":guia_guiaexame"    , $_POST['guia_guiaexame']);
        $query->bindParam(":preco_guiaexame" , $_POST['preco_guiaexame']);
        $query->bindParam(":prazo_guiaexame", $_POST['prazo_guiaexame']);
        $query->bindParam(":exame_guiaexame"  , $_POST['exame_guiaexame']);
        $query->bindParam(":id_guiaexame"      , $_POST['id_guiaexame']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('guiaexame alterado com sucesso');
        }else{
            $this->retornaOK('Nenhum dado alterado');
        }
    }

    public function excluirExame(){
        $db = Conexao::connect();

        $sql = "DELETE FROM guiasexames WHERE id_guiaexame=:id_guiaexame";

        $query = $db->prepare($sql);
        $query->bindParam(":id_guiaexame", $_POST['id_guiaexame']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('guiaexame excluído com sucesso');
        }else{
            $this->retornaErro('Erro ao excluir guiaexame');
        }
    
    }


    public function bootgridCadastrarExame()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $id_guia = addslashes($_POST['id_guia']);
        $sql = "SELECT `id_guiaexame`, `preco_guiaexame`, `prazo_guiaexame`, `exame_guiaexame`, `nome_exame`, `preco_exame`, `tempo_exame` FROM guiasexames INNER JOIN exames ON exame_guiaexame = id_exame INNER JOIN guias ON guia_guiaexame = id_guia WHERE guia_guiaexame = {$id_guia} ";

        if ($busca!=''){
            $sql .= " and (
                        data_guiaexame LIKE '%{$busca}%' OR
                        nome_exame LIKE '%{$busca}%' OR
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

    public function bootgridEditarExame()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $id_guia = addslashes($_POST['id_guia']);
        $sql = "SELECT `id_guiaexame`, `preco_guiaexame`, `prazo_guiaexame`, `exame_guiaexame`, `nome_exame`, `preco_exame`, `tempo_exame` FROM guiasexames INNER JOIN exames ON exame_guiaexame = id_exame INNER JOIN guias ON guia_guiaexame = id_guia WHERE guia_guiaexame = {$id_guia} ";

        if ($busca!=''){
            $sql .= " and (
                        data_guiaexame LIKE '%{$busca}%' OR
                        nome_exame LIKE '%{$busca}%' OR
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }
    public function AtualizaValor()
    {
        $db =  Conexao::connect();

        $query = $db->prepare("SELECT * FROM guias WHERE id_guia=:id_guia");
        $query->bindValue(":id_guia", $_GET['id_guia']);
        $query->execute();
    
        $linha = $query->fetchObject();
    
        $ret['precototal_guia'] = $linha->precototal_guia;
    
        echo json_encode($ret);
    }
    public function AtualizaPrazo()
    {
        $db =  Conexao::connect();

        $query = $db->prepare("SELECT * FROM guias WHERE id_guia=:id_guia");
        $query->bindValue(":id_guia", $_GET['id_guia']);
        $query->execute();
    
        $linha = $query->fetchObject();
    
        $ret['prazofinal_guia'] = $linha->prazofinal_guia;
    
        echo json_encode($ret);
    }
    
public function formCadastrarResultado($id_guia)
{
    $db = Conexao::connect();

    date_default_timezone_set('America/Sao_Paulo');
    $data_resultado = date("d-m-Y");

    $sql = "SELECT * FROM resultados ORDER BY data_resultado";
    $query2 = $db->prepare($sql);
    $resultado2 = $query2->execute();

    $linha2 = $query2->fetch();

    $sql = "SELECT * FROM guiasexames ORDER BY guia_guiaexame";
    $query_guiasexames = $db->prepare($sql);
    $resultado_guiasexames = $query_guiasexames->execute();

    $guiasexames = $query_guiasexames->fetch();

    $sql = "SELECT `id_guia`, `data_guia`, `cliente_guia`, `medico_guia`, `convenio_guia`, `codigo_guia`, `senha_guia`,`precototal_guia`, `prazofinal_guia`, `nome_medico`, `nome_cliente`, `nome_convenio` FROM guias INNER JOIN medicos ON medico_guia = id_medico INNER JOIN clientes ON cliente_guia = id_cliente INNER JOIN convenios ON convenio_guia = id_convenio WHERE id_guia=:id_guia LIMIT 0, 1";
    $query = $db->prepare($sql);
    $query->bindParam(":id_guia", $id_guia);
    $resultado = $query->execute();

    $linha = $query->fetch();

    $sql = "SELECT * FROM medicos ORDER BY nome_medico";
    $query_medicos = $db->prepare($sql);
    $resultado_medicos = $query_medicos->execute();

    $medicos = $query_medicos->fetchAll();

    $sql = "SELECT * FROM clientes ORDER BY nome_cliente";
    $query_clientes = $db->prepare($sql);
    $resultado_clientes = $query_clientes->execute();

    $clientes = $query_clientes->fetchAll();

    $sql = "SELECT * FROM convenios ORDER BY nome_convenio";
    $query_convenios = $db->prepare($sql);
    $resultado_convenios = $query_convenios->execute();

    $convenios = $query_convenios->fetchAll();

    $sql = "SELECT * FROM exames ORDER BY nome_exame";
    $query_exames = $db->prepare($sql);
    $resultado_exames = $query_exames->execute();

    $exames = $query_exames->fetchAll();

    echo $this->template->twig->render('guia/cadastrarResultado.html.twig', compact('linha', 'linha2','guiasexames', 'medicos', 'clientes', 'convenios','exames', 'data_resultado'));
}

public function formEditarResultado($id_resultado)
{
    $db = Conexao::connect();

    $sql = "SELECT * FROM resultados WHERE id_resultado=:id_resultado LIMIT 0, 1";

    $query = $db->prepare($sql);
    $query->bindParam(":id_resultado", $id_resultado);
    $resultado = $query->execute();

    $linha = $query->fetch();

    $sql = "SELECT * FROM exames ORDER BY nome_exame";
    $query_exames = $db->prepare($sql);
    $resultado_exames = $query_exames->execute();

    $exames = $query_exames->fetchAll();

    $sql = "SELECT * FROM guias ORDER BY data_guia";
    $query_guias = $db->prepare($sql);
    $resultado_guias = $query_guias->execute();

    $guias = $query_guias->fetchAll();

    echo $this->template->twig->render('guia/editarResultado.html.twig', compact('linha', 'exames', 'guias'));
}



public function salvarCadastrarResultado()
{
    $db = Conexao::connect();

    $sql = "INSERT INTO resultados (data_resultado, guia_resultado, responsavel_resultado, resultado, laudo_resultado, observacao_resultado)
            VALUES (:data_resultado, :guia_resultado, :responsavel_resultado, :resultado, :laudo_resultado, :observacao_resultado)";

    $query = $db->prepare($sql);
    $query->bindParam(":data_resultado"       , $_POST['data_resultado']);
    $query->bindParam(":guia_resultado"       , $_POST['guia_resultado']);
    $query->bindParam(":responsavel_resultado", $_POST['responsavel_resultado']);
    $query->bindParam(":resultado"            , $_POST['resultado']);
    $query->bindParam(":laudo_resultado"      , $_POST['laudo_resultado']);
    $query->bindParam(":observacao_resultado" , $_POST['observacao_resultado']);
    $query->execute();

    if ($query->rowCount()==1) {
        $this->retornaOK('Resultado cadastrado com sucesso');
    }else{
        $this->retornaErro('Erro ao inserir os dados');
    }
}

public function salvarEditarResultado()
{
    $db = Conexao::connect();

    $sql = "UPDATE guiasexames SET guia_guiaexame=:guia_guiaexame,prazo_guiaexame=:prazo_guiaexame,
                    exame_guiaexame=:exame_guiaexame,preco_guiaexame=:preco_guiaexame 
            WHERE id_guiaexame=:id_guiaexame";

    $query = $db->prepare($sql);
    $query->bindParam(":guia_guiaexame"    , $_POST['guia_guiaexame']);
    $query->bindParam(":preco_guiaexame" , $_POST['preco_guiaexame']);
    $query->bindParam(":prazo_guiaexame", $_POST['prazo_guiaexame']);
    $query->bindParam(":exame_guiaexame"  , $_POST['exame_guiaexame']);
    $query->bindParam(":id_guiaexame"      , $_POST['id_guiaexame']);
    $query->execute();

    if ($query->rowCount()==1) {
        $this->retornaOK('guiaexame alterado com sucesso');
    }else{
        $this->retornaOK('Nenhum dado alterado');
    }
}
}