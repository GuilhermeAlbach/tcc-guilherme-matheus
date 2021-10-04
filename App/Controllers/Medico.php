<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguroUsuario;

class Medico Extends ControllerSeguroUsuario
{
    public function index()
    {
        echo $this->template->twig->render('medico/listagem.html.twig');
    }

    public function formCadastrar()
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM especialidades ORDER BY especialidade";
        $query_especialidades = $db->prepare($sql);
        $resultado_especialidades = $query_especialidades->execute();

        $especialidades = $query_especialidades->fetchAll();

        echo $this->template->twig->render('medico/cadastrar.html.twig', compact('especialidades'));
    }

    public function formEditar($id_medico)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM medicos WHERE id_medico=:id_medico LIMIT 0, 1";

        $query = $db->prepare($sql);
        $query->bindParam(":id_medico", $id_medico);
        $resultado = $query->execute();

        $linha = $query->fetch();

        $sql = "SELECT * FROM especialidades ORDER BY especialidade";
        $query_especialidades = $db->prepare($sql);
        $resultado_especialidades = $query_especialidades->execute();

        $especialidades = $query_especialidades->fetchAll();

        echo $this->template->twig->render('medico/editar.html.twig', compact('linha', 'especialidades'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

        $sql = "INSERT INTO medicos (nome_medico, crm_medico, telefone_medico, especialidade_medico) 
                VALUES (:nome_medico, :crm_medico, :telefone_medico, :especialidade_medico)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_medico"         , $_POST['nome_medico']);
        $query->bindParam(":crm_medico"          , $_POST['crm_medico']);
        $query->bindParam(":telefone_medico"     , $_POST['telefone_medico']);
        $query->bindParam(":especialidade_medico", $_POST['especialidade_medico']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Médico cadastrado com sucesso');
        }else{
            $this->retornaErro('Erro ao inserir os dados');
        }
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        $sql = "UPDATE medicos SET nome_medico=:nome_medico,crm_medico=:crm_medico, 
                        telefone_medico=:telefone_medico, especialidade_medico=:especialidade_medico  
                WHERE id_medico=:id_medico";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_medico"         , $_POST['nome_medico']);
        $query->bindParam(":crm_medico"          , $_POST['crm_medico']);
        $query->bindParam(":telefone_medico"     , $_POST['telefone_medico']);
        $query->bindParam(":especialidade_medico", $_POST['especialidade_medico']);
        $query->bindParam(":id_medico"           , $_POST['id_medico']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Medico alterado com sucesso');
        }else{
            $this->retornaOK('Nenhum dado alterado');
        }
    }

    public function excluir(){
        try{
        $db = Conexao::connect();

        $sql = "DELETE FROM medicos WHERE id_medico=:id_medico";

        $query = $db->prepare($sql);
        $query->bindParam(":id_medico", $_POST['id_medico']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Medico excluído com sucesso');
        }else{
            $this->retornaErro('Erro ao excluir medico');
        }    
    }catch (\PDOException $exception){
            $this->retornaErro('Tipo não pode ser excluído.');
    }
    }


    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT `id_medico`, `nome_medico`, `crm_medico`, `telefone_medico`, `especialidade` FROM medicos INNER JOIN especialidades ON especialidade_medico = id_especialidade WHERE 1 ";

        if ($busca!=''){
            $sql .= " and (
                        nome_medico LIKE '%{$busca}%' OR
                        especialidade LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

}
