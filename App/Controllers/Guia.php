<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguroUsuario;
use App\PDF;
use DateTime;

class guia Extends ControllerSeguroUsuario
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
        $data_guia   = date("d-m-Y H:i:s");

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

        $sql = "SELECT `id_guia`, DATE_FORMAT(`data_guia`, '%d/%m/%Y - %H:%i') as `data_guia`, `cliente_guia`, `medico_guia`, `convenio_guia`, `codigo_guia`, `senha_guia`,`precototal_guia`, `prazofinal_guia`, `nome_medico`, `nome_cliente`, `nome_convenio`, `id_guiaexame`, `exame_guiaexame`, `guia_guiaexame`, `preco_guiaexame`, `prazo_guiaexame`, `nome_exame`, `precototal_guia`,`prazofinal_guia` FROM guias INNER JOIN medicos ON medico_guia = id_medico INNER JOIN clientes ON cliente_guia = id_cliente INNER JOIN convenios ON convenio_guia = id_convenio LEFT JOIN guiasexames ON guia_guiaexame = id_guia LEFT JOIN exames ON exame_guiaexame = id_exame WHERE id_guia=:id_guia LIMIT 0, 1";
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


        echo $this->template->twig->render('guia/editar.html.twig', compact('linha', 'medicos', 'clientes', 'convenios', 'exames'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO guias (data_guia, cliente_guia, convenio_guia, medico_guia,
                            codigo_guia,senha_guia) 
                VALUES (NOW(), :cliente_guia, :convenio_guia, :medico_guia, 
                        :codigo_guia, :senha_guia)";

        $query = $db->prepare($sql);
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

        $sql = "UPDATE guias SET medico_guia=:medico_guia,
                    cliente_guia=:cliente_guia, convenio_guia=:convenio_guia,codigo_guia=:codigo_guia,  
                    senha_guia=:senha_guia, prazofinal_guia=:prazofinal_guia, precototal_guia=:precototal_guia
                WHERE id_guia=:id_guia";

        $query = $db->prepare($sql);
   //     $query->bindParam(":data_guia"      , $_POST['data_guia']);
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
        $sql = "SELECT `id_guia`, DATE_FORMAT(`data_guia`, '%d/%m/%Y - %H:%i') as `data_guia`, `cliente_guia`, `medico_guia`, `convenio_guia`, `codigo_guia`, `senha_guia`, `nome_medico`, `nome_cliente`, `nome_convenio` FROM guias INNER JOIN medicos ON medico_guia = id_medico INNER JOIN clientes ON cliente_guia = id_cliente INNER JOIN convenios ON convenio_guia = id_convenio WHERE 1 ";

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
            $this->retornaOK('Exame cadastrado com sucesso');
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
            $this->retornaOK('Exame alterado com sucesso');
        }else{
            $this->retornaErro ('Nenhum dado alterado');
        }
    }

    public function excluirExame(){
        $db = Conexao::connect();

        $sql = "DELETE FROM guiasexames WHERE id_guiaexame=:id_guiaexame";

        $query = $db->prepare($sql);
        $query->bindParam(":id_guiaexame", $_POST['id_guiaexame']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('exame excluído com sucesso');
        }else{
            $this->retornaErro('Erro ao excluir o exame');
        }
    
    }

    public function bootgridEditarExame()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $id_guia = addslashes($_POST['id_guia']);
        $sql = "SELECT `id_guiaexame`, `preco_guiaexame`, `prazo_guiaexame`, `exame_guiaexame`, `nome_exame`, `preco_exame`, `tempo_exame`, `resultado`, `laudo_resultado` FROM guiasexames INNER JOIN exames ON exame_guiaexame = id_exame INNER JOIN guias ON guia_guiaexame = id_guia LEFT JOIN resultados ON id_guiaexame = guia_resultado WHERE guia_guiaexame = {$id_guia} ";
    
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
    

public function formEditarResultado($id_guia)
{
    $db = Conexao::connect();

    $sql = "SELECT `id_guia`, DATE_FORMAT(`data_guia`, '%d/%m/%Y - %H:%i') as `data_guia`, `cliente_guia`, `medico_guia`, `convenio_guia`, `codigo_guia`, `senha_guia`,`precototal_guia`, `prazofinal_guia`, `nome_medico`, `id_cliente`, `nome_cliente`, `sexo_cliente`, `datanascimento_cliente`, `nome_convenio`, `id_resultado`, `data_resultado`, `guia_resultado`, `resultado`, `responsavel_resultado`, `laudo_resultado`, `observacao_resultado`, `guia_guiaexame` FROM guias INNER JOIN medicos ON medico_guia = id_medico INNER JOIN clientes ON cliente_guia = id_cliente INNER JOIN convenios ON convenio_guia = id_convenio INNER JOIN guiasexames ON guia_guiaexame = id_guia INNER JOIN exames ON exame_guiaexame = id_exame LEFT JOIN resultados ON guia_resultado = id_guiaexame WHERE id_guia=:id_guia LIMIT 0, 1";
    $query = $db->prepare($sql);
    $query->bindParam(":id_guia", $id_guia);
    $resultado = $query->execute();

    $linha = $query->fetch();

    $sql = "SELECT * FROM usuarios ORDER BY nome_usuario";
    $query_usuarios = $db->prepare($sql);
    $resultado_usuarios = $query_usuarios->execute();

    $usuarios = $query_usuarios->fetchAll();

    echo $this->template->twig->render('guia/editarResultado.html.twig', compact('linha','usuarios'));
}

public function salvarCadastrarResultado()
{
    $db = Conexao::connect();

    $sql = "INSERT INTO resultados (data_resultado, guia_resultado, responsavel_resultado, resultado, laudo_resultado, observacao_resultado)
            VALUES (NOW(), :guia_resultado, :responsavel_resultado, :resultado, :laudo_resultado, :observacao_resultado)";

    $query = $db->prepare($sql);
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

    $sql = "UPDATE resultados SET guia_resultado=:guia_resultado,responsavel_resultado=:responsavel_resultado, data_resultado=NOW(),
                    resultado=:resultado,observacao_resultado=:observacao_resultado,laudo_resultado=:laudo_resultado
            WHERE id_resultado=:id_resultado";

    $query = $db->prepare($sql);
    $query->bindParam(":guia_resultado"       , $_POST['guia_resultado']);
    $query->bindParam(":responsavel_resultado", $_POST['responsavel_resultado']);
    $query->bindParam(":resultado"            , $_POST['resultado']);
    $query->bindParam(":laudo_resultado"      , $_POST['laudo_resultado']);
    $query->bindParam(":observacao_resultado" , $_POST['observacao_resultado']);
    $query->bindParam(":id_resultado"         , $_POST['id_resultado']);
    $query->execute();

    if ($query->rowCount()==1) {
        $this->retornaOK('Resultado alterado com sucesso');
    }else{
        $this->retornaOK('Nenhum dado alterado');
    }
}


public function formPDF($id_guia)
{
    $db = Conexao::connect();

    $agora = new DateTime("now");
    $nasc = new DateTime($_POST['datanascimento_cliente']);
    $idade_cliente = date_diff($nasc, $agora);
    $idade = $idade_cliente->format('%Y anos %m meses e %d dias');

    $sql = "SELECT `id_guia`, DATE_FORMAT(`data_guia`, '%d/%m/%Y %H:%i') as `data_guia`, `cliente_guia`, `medico_guia`, `convenio_guia`, `codigo_guia`, `senha_guia`,`precototal_guia`, `prazofinal_guia`,
                             `nome_medico`, 
                                `nome_cliente`, `sexo_cliente`,
                                    `nome_convenio`, 
                                        `nome_exame`
                                        `resultado`, DATE_FORMAT(`data_resultado`, '%d/%m/%Y' ) as `data_resultado`,
                                        `valorreferencia`, `valorreferencia_min`, `valorreferencia_max`, `idade_max`, `idade_min`,
                                        `unidademedida`,
                                        `metodo`, `material` FROM guias 
                                                INNER JOIN medicos ON medico_guia = id_medico 
                                                INNER JOIN clientes ON cliente_guia = id_cliente 
                                                INNER JOIN convenios ON convenio_guia = id_convenio 
                                                INNER JOIN guiasexames ON guia_guiaexame = :id_guia 
                                                INNER JOIN resultados ON guia_resultado = id_guiaexame
                                                INNER JOIN exames ON exame_guiaexame = id_exame 
                                                INNER JOIN valoresreferencia ON exame_valorreferencia = id_exame
                                                INNER JOIN unidadesmedida ON unidademedida_valorreferencia = id_unidademedida 
                                                INNER JOIN metodos ON metodo_exame = id_metodo 
                                                INNER JOIN materiais ON material_exame = id_material
                                                    WHERE id_guia=:id_guia LIMIT 0, 1";
    $query = $db->prepare($sql);
    $query->bindParam(":id_guia", $id_guia);
    $resultado = $query->execute();

    $linha = $query->fetch();

    $sql = "SELECT `id_guiaexame`, `preco_guiaexame`, `prazo_guiaexame`, `exame_guiaexame`, `nome_exame`, `preco_exame`, `tempo_exame`, `resultado`, `laudo_resultado`, `data_guia`, `id_guia`, `nome_medico`, `medico_guia`, `id_medico`, `nome_cliente`, `valorreferencia`, `valorreferencia_min`, `valorreferencia_max`, `idade_min`, `idade_max`, `sexo`, `unidademedida_valorreferencia`, `exame_valorreferencia`, `unidademedida` FROM guias INNER JOIN guiasexames ON guia_guiaexame = id_guia INNER JOIN exames ON exame_guiaexame = id_exame INNER JOIN medicos ON medico_guia = id_medico INNER JOIN clientes ON cliente_guia = id_cliente INNER JOIN resultados ON id_guiaexame = guia_resultado INNER JOIN valoresreferencia ON exame_valorreferencia = id_exame INNER JOIN unidadesmedida ON unidademedida_valorreferencia = id_unidademedida WHERE (id_guia=:id_guia) AND (idade_max > :idade_cliente OR idade_min < :idade_cliente) ORDER BY data_guia DESC";
    $query = $db->prepare($sql);
    $query->bindParam(":id_guia", $id_guia);
    $query->bindParam(":idade_cliente", $idade);
    $resultado = $query->execute();

    $guiasexames = $query->fetchAll();

    $sql = "SELECT * FROM usuarios ORDER BY nome_usuario";
    $query_usuarios = $db->prepare($sql);
    $resultado_usuarios = $query_usuarios->execute();

    $usuarios = $query_usuarios->fetchAll();

    $conteudoPDF =  $this->template->twig->render('guia/montaPDF.html.twig', compact('guiasexames', 'linha', 'usuarios', 'idade'));

//    echo $conteudoPDF;
//    exit;

    $pdf = new PDF();
    $pdf->exibir($conteudoPDF, uniqid());

}
public function MontaPDF($id_guia)
{
    require "../vendor/autoload.php";

/*
// reference the Dompdf namespace
   use Dompdf\Dompdf;

// instantiate and use the dompdf class
    $dompdf = new Dompdf();
    $dompdf->loadHtml('/guia/formPDF/');
// (Optional) Setup the paper size and orientation
    $dompdf->setPaper('A4', 'landscape');
// Render the HTML as PDF
    $dompdf->render();
// Output the generated PDF to Browser
    $dompdf->stream();

    ob_start();

    include('montaPDF.html.twig');
    include('cssVenda.html');
    $html = ob_get_clean();
    ob_end_clean();

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetFont('dejavusans', '', 10);
    $pdf->AddPage();

    $pdf->writeHTML($html, true, false, true, false, '');

    $pdf->Output('nome-do-arquivo.pdf','I');

    echo $html;
*/
}

}