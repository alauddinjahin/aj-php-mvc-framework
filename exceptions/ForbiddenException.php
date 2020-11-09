<?php
namespace aj\phpmvc\exceptions;

use Exception;

class ForbiddenException extends Exception
{
    protected $message  = 'You don\'t have permission to view this page';
    protected $code     = 403;

    





}