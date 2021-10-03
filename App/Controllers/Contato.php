<?php


namespace App\Controllers;

use App\Controller;
use App\Conexao;
use App\Bootgrid;
use App\ControllerSeguro;

class Contato Extends Controller
{
    public function index()
    {
        echo $this->template->twig->render('contato.html.twig');
    }
}
