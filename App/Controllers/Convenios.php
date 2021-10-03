<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;

class Convenios Extends Controller
{
    public function index()
    {
        $db = Conexao::connect();

        $sql = "SELECT `id_convenio`,`nome_convenio`, `responsavel_convenio`, `cnpj_convenio` FROM convenios ORDER BY id_convenio";
        $query = $db->prepare($sql);
        $resultado = $query->execute();

        $convenios = $query->fetchAll();

        echo $this->template->twig->render('convenios.html.twig', compact('convenios'));
    }

}