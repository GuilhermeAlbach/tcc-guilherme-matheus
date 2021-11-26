<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguroUsuario;

class usuario Extends ControllerSeguroUsuario
{
    public function index()
    {
        echo $this->template->twig->render('usuario/listagem.html.twig');
    }

    public function formCadastrar()
    {
        $db = Conexao::connect();
        
        $sql = "SELECT * FROM usuarios ORDER BY nome_usuario";
        $query_usuarios = $db->prepare($sql);
        $resultado_usuarios = $query_usuarios->execute();

        $usuarios = $query_usuarios->fetchAll();
       

        echo $this->template->twig->render('usuario/cadastrar.html.twig', compact('usuarios','exames'));
    }

    public function formEditar($id_usuario)
    {
        $db = Conexao::connect();

        $sql = "SELECT * FROM usuarios WHERE id_usuario=:id_usuario";

        $query = $db->prepare($sql);
        $query->bindParam(":id_usuario", $id_usuario);
        $resultado = $query->execute();

        $linha = $query->fetch();

        $sql = "SELECT * FROM usuarios ORDER BY nome_usuario";
        $query_usuarios = $db->prepare($sql);
        $resultado_usuarios = $query_usuarios->execute();

        $usuarios = $query_usuarios->fetchAll();
      

        echo $this->template->twig->render('usuario/editar.html.twig', compact('linha','usuarios'));
    }



    public function salvarCadastrar()
    {
        $db = Conexao::connect();

       try{

        if($this->validaCPF($_POST['cpf_usuario'])==false) {
            $this->retornaErro('CPF Inválido.');
        }

        $criptografaSenha = $this->criptografa($_POST['senha_usuario']);

        $sql = "INSERT INTO usuarios(nome_usuario,cpf_usuario,telefone_usuario,endereco_usuario,rg_usuario,
                            user_usuario,senha_usuario,celular_usuario,cidade_usuario,cep_usuario) 
                    VALUES (:nome_usuario,:cpf_usuario,:telefone_usuario,:endereco_usuario,:rg_usuario,
                            :user_usuario,:senha_usuario,:celular_usuario,:cidade_usuario,:cep_usuario)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_usuario"    , $_POST['nome_usuario']);
        $query->bindParam(":cpf_usuario"     , $_POST['cpf_usuario']);
        $query->bindParam(":rg_usuario"      , $_POST['rg_usuario']);
        $query->bindParam(":telefone_usuario", $_POST['telefone_usuario']);
        $query->bindParam(":celular_usuario" , $_POST['celular_usuario']);
        $query->bindParam(":endereco_usuario", $_POST['endereco_usuario']);
        $query->bindParam(":cidade_usuario"  , $_POST['cidade_usuario']);
        $query->bindParam(":cep_usuario"     , $_POST['cep_usuario']);
        $query->bindParam(":user_usuario"    , $_POST['user_usuario']);
        $query->bindParam(":senha_usuario"   , $criptografaSenha);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Usuário cadastrado com sucesso');
        }else{
            $this->retornaErro('Erro ao inserir os dados');
        }
    }catch (\Exception $error){
        $this->retornaErro('CPF já cadastrado');
    }

    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

    try{
        $sql = "SELECT `senha_usuario` FROM usuarios WHERE user_usuario=:user_usuario";
        $query = $db->prepare($sql);
        $query->bindParam(":user_usuario", $_POST['user_usuario']);
        $resultado = $query->execute();

        $linha = $query->fetchObject();
        if ($linha->senha_usuario != $_POST['senha_usuario']) {
            $criptografaSenha = $this->criptografa($_POST['senha_usuario']);
        }else{
            $criptografaSenha = $_POST['senha_usuario'];
        }


        if($this->validaCPF($_POST['cpf_usuario'])==false) {
            $this->retornaErro('CPF Inválido.');
        }

        $sql = "UPDATE usuarios SET nome_usuario=:nome_usuario,telefone_usuario=:telefone_usuario,celular_usuario=:celular_usuario,cpf_usuario=:cpf_usuario,
                                    endereco_usuario=:endereco_usuario, user_usuario=:user_usuario,rg_usuario=:rg_usuario,cidade_usuario=:cidade_usuario,cep_usuario=:cep_usuario,
                                    senha_usuario=:senha_usuario 
                WHERE id_usuario=:id_usuario";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_usuario"    , $_POST['nome_usuario']);
        $query->bindParam(":telefone_usuario", $_POST['telefone_usuario']);
        $query->bindParam(":celular_usuario" , $_POST['celular_usuario']);
        $query->bindParam(":cpf_usuario"     , $_POST['cpf_usuario']);
        $query->bindParam(":rg_usuario"      , $_POST['rg_usuario']);
        $query->bindParam(":endereco_usuario", $_POST['endereco_usuario']);
        $query->bindParam(":cidade_usuario"  , $_POST['cidade_usuario']);
        $query->bindParam(":cep_usuario"     , $_POST['cep_usuario']);
        $query->bindParam(":user_usuario"    , $_POST['user_usuario']);
        $query->bindParam(":senha_usuario"   , $criptografaSenha);
        $query->bindParam(":id_usuario"      , $_POST['id_usuario']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Usuário alterado com sucesso');
        }else{
            $this->retornaOK('Nenhum dado alterado');
        }
    }catch (\Exception $error){
        $this->retornaErro('CPF já cadastrado');
    }

    }

    public function excluir(){
        try{
        $db = Conexao::connect();

        $sql = "DELETE FROM usuarios WHERE id_usuario=:id_usuario";

        $query = $db->prepare($sql);
        $query->bindParam(":id_usuario", $_POST['id_usuario']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Excluído com sucesso');
        }else{
            $this->retornaErro('Erro ao excluir os dados');
        }
    }catch (\Exception $error){
        $this->retornaErro('Usuário não pode ser excluído');
    }

    }


    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT * FROM usuarios WHERE 1 ";
        if ($busca!=''){
            $sql .= " and (
                        nome_usuario LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }

    function validaCPF($cpf_usuario) {

        // Extrai somente os números
        $cpf_usuario = preg_replace( '/[^0-9]/is', '', $cpf_usuario );

        // Verifica se foi informado todos os digitos corretamente
        if (strlen($cpf_usuario) != 11) {
            return false;
        }

        // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
        if (preg_match('/(\d)\1{10}/', $cpf_usuario)) {
            return false;
        }

        // Faz o calculo para validar o CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf_usuario[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf_usuario[$c] != $d) {
                return false;
            }
        }
        return true;

    }


}
