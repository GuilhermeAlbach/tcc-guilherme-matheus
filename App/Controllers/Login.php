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

    public function verificar()
    {
        session_start();

        $db = Conexao::connect();

        $login = $_POST['login'];
        $senha = $_POST['senha'];

        $sql = "SELECT * FROM usuario WHERE login=:login AND senha=:senha";

        $resultados = $db ->prepare($sql);

        $resultados->bindParam(":login", $login);
        $resultados->bindParam(":senha", $senha);
        $resultados->execute();

        if($resultados->rowCount()==1){
            $linha = $resultados->fetch();

            $_SESSION['liberado'] = true;
            $_SESSION['id'] = $linha->id; //Código da Pessoa que está logada
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
