<?php

namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;

class Login Extends Controller
{
    public function index()
    {
        echo $this->template->twig->render('login/login.html.twig');
    }

    public function verificaSenha()
    {
        session_start();

        $db = Conexao::connect();

        $user_usuario = $_POST['user_usuario'];
        $senha_usuario = $_POST['senha_usuario'];

        $sql = "SELECT * FROM usuarios WHERE user_usuario=:user_usuario AND senha_usuario=:senha_usuario";

        $query = $db ->prepare($sql);
        $query->bindParam(":user_usuario", $user_usuario);
        $query->bindParam(":senha_usuario", $senha_usuario);
        $query->execute();

        if($query->rowCount()==1){
            $linha = $query->fetch();

            $_SESSION['liberado'] = true;
//            $_SESSION['id_usuario'] = $linha->id_usuario; //Código da Pessoa que está logada
            $this->retornaOK('Acesso autorizado.');

        }else{
            $_SESSION['liberado'] = false;
            $this->retornaErro('Usuário ou senha inválidos');
        }
    }

    public function sair()
    {
        session_start();
        //unset($_SESSION['liberado']);
        session_destroy();

        header("Location: /login");
    }
}
