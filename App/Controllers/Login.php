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
            header("location: /");
            exit;
        }
        
        echo $this->template->twig->render('login/login.html.twig');
    }
    
    public function verificarUsuario()
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

                $_SESSION['UsuarioLogado'] = true;
                $_SESSION['id_usuario'] = $linha->id_usuario;
                $this->retornaOK('Acesso autorizado.');
            }else{
                $_SESSION['Usuariologado'] = false;


                $db = Conexao::connect();

                $sql = "SELECT * FROM clientes WHERE usuario_cliente=:user_usuario AND senha_cliente=:senha_usuario";
    
                $resultados = $db ->prepare($sql);
    
                $resultados->bindParam(":user_usuario", $usuario);
                $resultados->bindParam(":senha_usuario", $senha);
                $resultados->execute();
    
                if($resultados->rowCount()==1){
                    $linha = $resultados->fetchObject();
    
                    $_SESSION['ClienteLogado'] = true;
                    $_SESSION['UsuarioLogado'] = false;
                    $_SESSION['id_cliente'] = $linha->id_cliente;
                    header("Location: /AcessoCliente/Editar/" . $_SESSION['id_cliente']);
                    exit;
                }else{
                    $_SESSION['ClienteLogado'] = false;
                    $_SESSION['UsuarioLogado'] = false;

                    $db = Conexao::connect();

                    $usuario = $_POST['user_usuario'];
                    $senha = $_POST['senha_usuario'];
        
                    $sql2 = "SELECT `id_guia`, `data_guia`, `cliente_guia`, `convenio_guia`, `medico_guia`, `codigo_guia`, `senha_guia`, `id_cliente`, `datanascimento_cliente` FROM guias INNER JOIN clientes ON cliente_guia = id_cliente WHERE codigo_guia=:user_usuario AND senha_guia=:senha_usuario";
        
                    $resultados2 = $db ->prepare($sql2);
        
                    $resultados2->bindParam(":user_usuario" , $usuario);
                    $resultados2->bindParam(":senha_usuario", $senha);
                    $resultados2->execute();
        
                    if($resultados2->rowCount()==1){
                        $linha2 = $resultados2->fetchObject();
        
                        $_SESSION['ClienteLogado'] = true;
                        $_SESSION['UsuarioLogado'] = false;    
                        $_SESSION['id_guia'] = $linha2->id_guia;
                        $_SESSION['datanascimento_cliente'] = $linha2->datanascimento_cliente;
                        header("Location: /AcessoCliente/FormPDF/" . $_SESSION['id_guia']);
                        exit;
                    }else{
                        $_SESSION['ClienteLogado'] = false;
                        $_SESSION['UsuarioLogado'] = false;    
                        $this->retornaErro('Código ou senha incorretos');
                    }
        
                }
    
            }
        }catch (\Exception $error){
            $this->retornaErro('Erro de BD. <br>' . $error->getMessage());
        }
    }
      
    public function verificarGuia()
    {
        session_start();

        try{

            $db = Conexao::connect();

            $usuario = $_POST['user_usuario'];
            $senha = $_POST['senha_usuario'];

            $sql2 = "SELECT * FROM guias WHERE codigo_guia=:user_usuario AND senha_guia=:senha_usuario";

            $resultados2 = $db ->prepare($sql2);

            $resultados2->bindParam(":user_usuario" , $usuario);
            $resultados2->bindParam(":senha_usuario", $senha);
            $resultados2->execute();

            if($resultados2->rowCount()==1){
                $linha2 = $resultados2->fetchObject();

                $_SESSION['GuiaLogado'] = true;
                $_SESSION['id_guia'] = $linha2->id_guia;
                $this->retornaOK('Acesso autorizado.');
            }else{
                $_SESSION['GuiaLogado'] = false;
                $this->retornaErro('Código ou senha incorretos');
            }
        }catch (\Exception $error){
            $this->retornaErro('Erro de BD. <br>' . $error->getMessage());
        }
    }

    public function sair()
    {
        session_start();
        unset($_SESSION['UserLogado']);
        unset($_SESSION['ClienteLogado']);
        unset($_SESSION['GuiaLogado']);
        session_destroy();

        header("Location: /");
    }
}
