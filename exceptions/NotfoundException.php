<?php
namespace aj\phpmvc\exceptions;

use Exception;

class NotfoundException extends Exception
{
    protected $message  = 'The page you are looking, Page is not Found!';
    protected $code     = 404;  





}