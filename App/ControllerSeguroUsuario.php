<?php

namespace App;

class ControllerSeguroUsuario extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!isset($_SESSION['UsuarioLogado']) || $_SESSION['UsuarioLogado'] != true) {
            //\App\Controller::errorPermission();
            header("Location: /login");
        }

    }

}
