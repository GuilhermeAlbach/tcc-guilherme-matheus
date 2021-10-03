<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class Profissionais Extends Controller
{
    public function index()
    {
        $db = Conexao::connect();
        
        $sql = "SELECT `nome_usuario` FROM usuarios WHERE id_usuario = 1";
        $query = $db->prepare($sql);
        $resultado = $query->execute();

        $linha = $query->fetchAll();
       

        echo $this->template->twig->render('/Profissionais.html.twig', compact('linha'));
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
