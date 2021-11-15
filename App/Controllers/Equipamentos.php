<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;

class Equipamentos Extends Controller
{
    public function index(){

        echo $this->template->twig->render('Equipamentos.html.twig');

    }
}
