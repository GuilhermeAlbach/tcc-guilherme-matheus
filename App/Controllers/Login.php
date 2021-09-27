<?php

namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;

class login Extends Controller
{
    public function index()
    {
        session_start();
        if(isset($_SESSION['logado']) && $_SESSION['logado']){
            header("location: /pessoas");
            exit;
        }

        echo $this->template->twig->render('login/login.html.twig');
    }

    public function verificar()
    {
        session_start();

        try{

            $db = Conexao::connect();

            $usuario = $_POST['user_usuario'];
            $senha = $_POST['senha_usuario'];

            $senhacriptografada = $this->criptografa($senha);

            $sql = "SELECT * FROM usuarios WHERE user_usuario=:user_usuario AND senha_usuario=:senha_usuario";

            $resultados = $db ->prepare($sql);

            $resultados->bindParam(":user_usuario", $usuario);
            $resultados->bindParam(":senha_usuario", $senhacriptografada);
            $resultados->execute();

            if($resultados->rowCount()==1){
                $linha = $resultados->fetchObject();

                $_SESSION['logado'] = true;
                $_SESSION['id_usuario'] = $linha->id_usuario;
                $this->retornaOK('Acesso autorizado.');
            }else{
                $_SESSION['logado'] = false;
                $this->retornaErro('Usuário ou senha inválidos');
            }
        }catch (\Exception $error){
            $this->retornaErro('Erro de BD. <br>' . $error->getMessage());
        }

    }

    public function sair()
    {
        session_start();
        unset($_SESSION['logado']);
        session_destroy();

        header("Location: /login");
    }
}
