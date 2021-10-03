<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class Exames Extends Controller
{
    public function index()
    {
        echo $this->template->twig->render('exames.html.twig');
    }


    public function bootgrid()
    {
        $busca = addslashes($_POST['searchPhrase']);
        $sql = "SELECT `nome_exame`, `sexo_exame`, `descricao_exame`, `metodo` FROM exames INNER JOIN metodos ON metodo_exame = id_metodo WHERE 1";
        if ($busca!=''){
            $sql .= " and (
                nome_exame LIKE '%{$busca}%' OR
                metodo LIKE '%{$busca}%'
                        ) ";
        }

        $bootgrid = new Bootgrid($sql);
        echo $bootgrid->show();
    }
}