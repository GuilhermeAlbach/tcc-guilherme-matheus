<?php


namespace App;


class Route
{

    public function __construct()
    {
        $this->run($this->getUrl());
    }

    protected function run($url)
    {
        $route = explode("/", $url);
        if ($route[1]=='')
            $route[1]=$route[2]='index';

        if (!isset($route[2]) || $route[2]=='')
            $route[2]='index';

        $class = "App\\Controllers\\".ucfirst($route[1]);
        if (!class_exists($class))
            return $this->error404("Controller Not Found: " . ucfirst($route[1]));

        $controller = new $class;
        $action = $route[2];

        if (!method_exists($controller, $action))
            return $this->error404("Method Not Found");

        $controller->$action((isset($route[3])?$route[3]:null));
    }

    protected function getUrl()
    {
        return parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
    }

    static function error404($error=""){

        http_response_code(404);
        $template = new Template();
        echo $template->twig->render('404.html.twig', compact("error"));
        exit;
    }

}
