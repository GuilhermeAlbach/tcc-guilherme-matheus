<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguroCliente;
use DateTime;

class AcessoCliente Extends ControllerSeguroCliente
{
    public function index()
    {
        echo $this->template->twig->render('AcessoCliente/listagem.html.twig');
    }

    public function ListaDeExames($id_cliente)
    {
        $db = Conexao::connect();

        $sqlGuia = "SELECT `id_guia`, `data_guia`, `prazofinal_guia`, `nome_medico`, `nome_cliente`, `id_cliente`, `datanascimento_cliente`, `sexo_cliente` FROM guias INNER JOIN clientes ON cliente_guia = id_cliente INNER JOIN convenios ON convenio_guia = id_convenio INNER JOIN medicos ON medico_guia = id_medico WHERE cliente_guia=:id_cliente ORDER BY data_guia DESC";
        $queryGuia = $db->prepare($sqlGuia);
        $queryGuia->bindParam(":id_cliente", $id_cliente);
        $resultadoGuia = $queryGuia->execute();

        $agora = new DateTime("now");
        $nasc = new DateTime($_POST['datanascimento_cliente']);
        $idade_cliente = date_diff($nasc, $agora);
        $idade = $idade_cliente->format('%Y');

        while ($guia = $queryGuia->fetchObject()){
            $guiasDado = $guia;

            $sql = "SELECT `id_guiaexame`, `preco_guiaexame`, `prazo_guiaexame`, `exame_guiaexame`, `nome_exame`, `preco_exame`, `tempo_exame`, `resultado`, `laudo_resultado`, `data_guia`, `id_guia`, `nome_medico`, `medico_guia`, `id_medico`, `nome_cliente`, `valorreferencia`, `valorreferencia_min`, `valorreferencia_max`, `idade_min`, `idade_max`, `sexo`, `unidademedida_valorreferencia`, `exame_valorreferencia`, `unidademedida` FROM guias INNER JOIN guiasexames ON guia_guiaexame = id_guia INNER JOIN exames ON exame_guiaexame = id_exame INNER JOIN medicos ON medico_guia = id_medico INNER JOIN clientes ON cliente_guia = id_cliente INNER JOIN resultados ON id_guiaexame = guia_resultado INNER JOIN valoresreferencia ON exame_valorreferencia = id_exame INNER JOIN unidadesmedida ON unidademedida_valorreferencia = id_unidademedida WHERE (id_guia=:id_guia) AND (idade_max > :idade_cliente OR idade_min < :idade_cliente) AND (sexo=:sexo_cliente OR sexo= 'Ambos') ORDER BY data_guia DESC";
            $queryExame = $db->prepare($sql);
            $queryExame->bindParam(":id_cliente", $id_cliente);
            $queryExame->bindParam(":sexo_cliente", $_POST['sexo_cliente']);
            $queryExame->bindParam(":idade_cliente", $idade);
            $queryExame->bindParam(":id_guia", $guia->id_guia);
            $queryExame->execute();

            while($exame = $queryExame->fetchObject()){
                $guiasDado->exames[] = $exame;
            }

            $guiaDados[] = $guiasDado;
        }

        $guiaDados = json_decode(json_encode($guiaDados, false));
        //$guiaDados = (object) $guiaDados;



        echo $this->template->twig->render('AcessoCliente/lista.html.twig', compact('guiaDados', 'idade'));
    }

    public function Editar($id_cliente)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM clientes WHERE id_cliente=:id_cliente";
        $query = $db->prepare($sql);
        $query->bindParam(":id_cliente", $id_cliente);
        $resultado = $query->execute();

        $linha = $query->fetch();

        echo $this->template->twig->render('AcessoCliente/editar.html.twig', compact('linha'));
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        if($this->validaCPF($_POST['cpf_cliente'])==false) {
            $this->retornaErro('CPF Inválido.');
        }

        $sql = "UPDATE clientes SET nome_cliente=:nome_cliente, endereco_cliente=:endereco_cliente, 
                        cpf_cliente=:cpf_cliente,rg_cliente=:rg_cliente,cep_cliente=:cep_cliente, telefone_cliente=:telefone_cliente, celular_cliente=:celular_cliente,
                        senha_cliente=:senha_cliente, sexo_cliente=:sexo_cliente, datanascimento_cliente=:datanascimento_cliente, 
                        observacao_cliente=:observacao_cliente, usuario_cliente=:usuario_cliente 
                WHERE id_cliente=:id_cliente";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_cliente"          , $_POST['nome_cliente']);
        $query->bindParam(":endereco_cliente"      , $_POST['endereco_cliente']);
        $query->bindParam(":cpf_cliente"           , $_POST['cpf_cliente']);
        $query->bindParam(":cep_cliente"           , $_POST['cep_cliente']);
        $query->bindParam(":rg_cliente"            , $_POST['rg_cliente']);
        $query->bindParam(":telefone_cliente"      , $_POST['telefone_cliente']);
        $query->bindParam(":celular_cliente"       , $_POST['celular_cliente']);
        $query->bindParam(":sexo_cliente"          , $_POST['sexo_cliente']);
        $query->bindParam(":datanascimento_cliente", $_POST['datanascimento_cliente']);
        $query->bindParam(":observacao_cliente"    , $_POST['observacao_cliente']);
        $query->bindParam(":usuario_cliente"       , $_POST['usuario_cliente']);
        $query->bindParam(":senha_cliente"         , $_POST['senha_cliente']);
        $query->bindParam(":id_cliente"            , $_POST['id_cliente']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Cliente alterado com sucesso');
        }else{
            $this->retornaOK('Nenhum dado alterado');
        }
    }

    function validaCPF($cpf_cliente) {

        // Extrai somente os números
        $cpf_cliente = preg_replace( '/[^0-9]/is', '', $cpf_cliente );

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf_cliente) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf_cliente)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf_cliente[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf_cliente[$c] != $d) {
                return false;
            }
        }
        return true;

    }


    public function formPDF($id_guia)
    {
    $db = Conexao::connect();

    $agora = new DateTime("now");
    $nasc = new DateTime($_POST['datanascimento_cliente']);
    $idade_cliente = date_diff($nasc, $agora);
    $idade = $idade_cliente->format('%Y anos %m meses e %d dias');

    $sql = "SELECT `id_guia`, `data_guia`, `cliente_guia`, `medico_guia`, `convenio_guia`, `codigo_guia`, `senha_guia`,`precototal_guia`, `prazofinal_guia`,
                             `nome_medico`, 
                                `nome_cliente`, `sexo_cliente`,
                                    `nome_convenio`, 
                                        `nome_exame`
                                        `resultado`, `data_resultado`,
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

    $sql = "SELECT `id_guiaexame`, `preco_guiaexame`, `prazo_guiaexame`, `exame_guiaexame`, `nome_exame`, `preco_exame`, `tempo_exame`, `resultado`, `laudo_resultado`, `data_guia`, `id_guia`, `nome_medico`, `medico_guia`, `id_medico`, `nome_cliente`, `valorreferencia`, `valorreferencia_min`, `valorreferencia_max`, `idade_min`, `idade_max`, `sexo`, `unidademedida_valorreferencia`, `exame_valorreferencia`, `unidademedida` FROM guias INNER JOIN guiasexames ON guia_guiaexame = id_guia INNER JOIN exames ON exame_guiaexame = id_exame INNER JOIN medicos ON medico_guia = id_medico INNER JOIN clientes ON cliente_guia = id_cliente INNER JOIN resultados ON id_guiaexame = guia_resultado INNER JOIN valoresreferencia ON exame_valorreferencia = id_exame INNER JOIN unidadesmedida ON unidademedida_valorreferencia = id_unidademedida WHERE (id_guia=:id_guia) AND (idade_max >= :idade_cliente OR idade_min <= :idade_cliente) AND (sexo = :sexo_cliente OR sexo='Ambos') ORDER BY data_guia DESC";
    $query = $db->prepare($sql);
    $query->bindParam(":id_guia", $id_guia);
    $query->bindParam(":idade_cliente", $idade);
    $query->bindParam(":sexo_cliente", $_POST['sexo_cliente']);
    $resultado = $query->execute();

    $guiasexames = $query->fetchAll();



    $sql = "SELECT * FROM usuarios ORDER BY nome_usuario";
    $query_usuarios = $db->prepare($sql);
    $resultado_usuarios = $query_usuarios->execute();

    $usuarios = $query_usuarios->fetchAll();

    echo $this->template->twig->render('guia/montaPDF.html.twig', compact('guiasexames', 'linha', 'usuarios', 'idade'));
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