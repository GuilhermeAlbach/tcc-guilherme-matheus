<?php

namespace App;

class ControllerSeguroCliente extends Controller
{
    public function __construct()
    {
        parent::__construct();

        if (!isset($_SESSION['ClienteLogado']) || $_SESSION['ClienteLogado'] != true) {
            //\App\Controller::errorPermission();
            header("Location: /login");
        }

    }

}
