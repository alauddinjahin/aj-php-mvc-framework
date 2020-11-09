<?php
namespace app\core;

use app\core\middlewares\BaseMiddleware;

class Controller
{

    public string $layout="main";
    public string $action="";
    protected array $middlewares=[]; // it is for BaseMiddleware array

    public function render($view,$params=[])
    {
        
        return Application::$app->view->renderView($view,$params);
        
    }

    public function setLayout($layout)
    {
        
        return $this->layout = $layout;
        
    }


    public function registerMiddleware(BaseMiddleware $baseMiddleware)
    {
        $this->middlewares[]=$baseMiddleware;
    }


    public function getMiddleware(): array
    {
        return $this->middlewares;
    }




}