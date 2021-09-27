<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class usuario Extends ControllerSeguro
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

        $criptografaSenha = $this->criptografa($_POST['senha_usuario']);

        $sql = "INSERT INTO usuarios(nome_usuario,cpf_usuario,telefone_usuario,endereco_usuario,
                            user_usuario,senha_usuario) 
                    VALUES (:nome_usuario,:cpf_usuario,:telefone_usuario,:endereco_usuario,
                            :user_usuario,:senha_usuario)";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_usuario"    , $_POST['nome_usuario']);
        $query->bindParam(":cpf_usuario"     , $_POST['cpf_usuario']);
        $query->bindParam(":telefone_usuario", $_POST['telefone_usuario']);
        $query->bindParam(":endereco_usuario", $_POST['endereco_usuario']);
        $query->bindParam(":user_usuario"    , $_POST['user_usuario']);
        $query->bindParam(":senha_usuario"   , $criptografaSenha);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Usuário cadastrado com sucesso');
        }else{
            $this->retornaErro('Erro ao inserir os dados');
        }
    }

    public function salvarEditar()
    {
        $db = Conexao::connect();

        $sql = "UPDATE usuarios SET nome_usuario=:nome_usuario,telefone_usuario=:telefone_usuario,cpf_usuario=:cpf_usuario,
                                    endereco_usuario=:endereco_usuario, user_usuario=:user_usuario,
                                    senha_usuario=:senha_usuario 
                WHERE id_usuario=:id_usuario";

        $query = $db->prepare($sql);
        $query->bindParam(":nome_usuario"    , $_POST['nome_usuario']);
        $query->bindParam(":telefone_usuario", $_POST['telefone_usuario']);
        $query->bindParam(":cpf_usuario"     , $_POST['cpf_usuario']);
        $query->bindParam(":endereco_usuario", $_POST['endereco_usuario']);
        $query->bindParam(":user_usuario"    , $_POST['user_usuario']);
        $query->bindParam(":senha_usuario"   , $_POST['senha_usuario']);
        $query->bindParam(":id_usuario"      , $_POST['id_usuario']);
        $query->execute();

        if ($query->rowCount()==1) {
            $this->retornaOK('Usuário alterado com sucesso');
        }else{
            $this->retornaOK('Nenhum dado alterado');
        }
    }

    public function excluir(){
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

}
