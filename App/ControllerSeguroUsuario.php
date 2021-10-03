<?php

namespace App;

class ControllerSeguroUsuario extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['UsuarioLogado']) || $_SESSION['UsuarioLogado'] != true) {
            //\App\Controller::errorPermission();
            header("Location: /login");
        }

        $this->template->twig->addGlobal('session', $_SESSION);
    }

}
