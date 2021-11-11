<?php

namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;


class login Extends Controller
{
    
    public function index()
    {
        if(isset($_SESSION['ClienteLogado']) || isset($_SESSION['UsuarioLogado'])){
            header("location: /");
            exit;
        }
        
        echo $this->template->twig->render('login/login.html.twig');
    }
    
    public function verificarUsuario()
    {
        
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
                unset($_SESSION['ClienteLogado']);
                $_SESSION['id_usuario'] = $linha->id_usuario;
                $_SESSION['nome_usuario'] = $linha->nome_usuario;

                $retorno['status'] = 1;
                $retorno['mensagem'] = 'Acesso autorizado';
                $retorno['url'] = "/guia";

                echo $this->jsonResponse($retorno);
                exit;

            }else{
                $_SESSION['Usuariologado'] = false;


                $db = Conexao::connect();

                $sql = "SELECT `datanascimento_cliente`, `id_cliente`, `sexo_cliente`, `nome_cliente`, `usuario_cliente`, `senha_cliente` FROM clientes WHERE usuario_cliente=:user_usuario AND senha_cliente=:senha_usuario";
    
                $resultados = $db ->prepare($sql);
    
                $resultados->bindParam(":user_usuario", $usuario);
                $resultados->bindParam(":senha_usuario", $senha);
                $resultados->execute();
    
                if($resultados->rowCount()==1){
                    $linha = $resultados->fetchObject();
    
                    $_SESSION['ClienteLogado'] = true;
                    unset($_SESSION['UsuarioLogado']);
                    $_SESSION['id_cliente'] = $linha->id_cliente;
                    $_SESSION['datanascimento_cliente'] = $linha->datanascimento_cliente;
                    $_SESSION['sexo_cliente'] = $linha->sexo_cliente;
                    $_SESSION['nome_cliente'] = $linha->nome_cliente;

                    $retorno['status'] = 1;
                    $retorno['mensagem'] = 'Acesso autorizado';
                    $retorno['url'] = "/AcessoCliente/Editar/" . $_SESSION['id_cliente'];

                    echo $this->jsonResponse($retorno);
                    exit;
                }else{
                    $_SESSION['ClienteLogado'] = false;
                    $_SESSION['UsuarioLogado'] = false;

                    $db = Conexao::connect();

                    $usuario = $_POST['user_usuario'];
                    $senha = $_POST['senha_usuario'];
        
                    $sql2 = "SELECT `id_guia`, `data_guia`, `cliente_guia`, `convenio_guia`, `medico_guia`, `codigo_guia`, `senha_guia`, `id_cliente`, `nome_cliente`, `datanascimento_cliente` FROM guias INNER JOIN clientes ON cliente_guia = id_cliente WHERE codigo_guia=:user_usuario AND senha_guia=:senha_usuario";
        
                    $resultados2 = $db ->prepare($sql2);
        
                    $resultados2->bindParam(":user_usuario" , $usuario);
                    $resultados2->bindParam(":senha_usuario", $senha);
                    $resultados2->execute();
        
                    if($resultados2->rowCount()==1){
                        $linha = $resultados2->fetchObject();
        
                        $_SESSION['ClienteLogado'] = true;
                        unset($_SESSION['UsuarioLogado']);
                        $_SESSION['id_guia'] = $linha->id_guia;
                        $_SESSION['id_cliente'] = $linha->id_cliente;
                        $_SESSION['nome_cliente'] = $linha->nome_cliente;
                        $_SESSION['datanascimento_cliente'] = $linha->datanascimento_cliente;

                        $retorno['status'] = 1;
                        $retorno['mensagem'] = 'Acesso autorizado';
                        $retorno['url'] = "/AcessoCliente/FormPDF/" . $_SESSION['id_guia'];

                        echo $this->jsonResponse($retorno);
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
    public function sair()
    {
        session_start();
        unset($_SESSION['UsuarioLogado']);
        unset($_SESSION['ClienteLogado']);
        session_destroy();

        header("Location: /");
    }
}
