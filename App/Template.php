<?php

namespace App;

use Twig;

class Template
{
    protected $loader;
    public $twig;

    public function __construct()
    {
        $this->loader = new \Twig\Loader\FilesystemLoader(VIEWPATH);
        $this->twig = new \Twig\Environment($this->loader, [
            //'cache' => VIEWCACHE,
        ]);
    }
}
