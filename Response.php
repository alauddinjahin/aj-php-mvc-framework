<?php
namespace app\core;
use app\core\Request;

class Response
{

    public function setStatusCode(int $code)
    {
        
        return http_response_code($code);
        
    }

    public function redirect(string $url)
    {
        header('location:'.$url);
        
    }




}