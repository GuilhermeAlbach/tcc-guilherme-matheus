<?php

namespace App;

use App\Conexao;

class Bootgrid
{
    public $current;
    public $rowCount;
    public $total;
    public $rows;

    public function setCurrent($value){
        if ( is_numeric($value) ) $this->current = $value;
    }

    public function __construct($sql)
    {
        $quantidade = ($_POST['rowCount'] <> 0 ? $_POST['rowCount'] : 0);
        $pagina = $_POST['current'];
        $inicio = ($pagina - 1 ) * $quantidade;

        $db = Conexao::connect();

        $resultados = $db->query($sql);
        $this->total = $resultados->rowCount();

        $sql .= " ORDER BY ";
        foreach ($_POST['sort'] as $campo => $tipo_order) {
            $sql .= $campo . " "  . $tipo_order;
        }

        if ($quantidade<>-1){
            $sql .=  " LIMIT {$inicio} , {$quantidade} ";
        }

        $resultados = $db->query($sql);

        $this->current = $pagina;
        $this->rowCount = $resultados->rowCount();
        $this->rows = $resultados->fetchALl();

    }


    public function show(){
        $ret['current'] = $this->current;
        $ret['rowCount'] = $this->rowCount;
        $ret['total'] = $this->total;
        $ret['rows'] = $this->rows;

        echo json_encode($ret);

    }
}
