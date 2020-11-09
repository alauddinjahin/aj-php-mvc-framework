<?php
namespace aj\phpmvc;
class Session
{

    protected const FLUSH_KEY="flush_messages";

    public function __construct()
    {
        session_start();

        $flushMessages = $_SESSION[self::FLUSH_KEY] ?? [];

        foreach ($flushMessages as $key => &$flushMessage) {
            // mark to be removed
            $flushMessage['remove'] = true;

        }

        $_SESSION[self::FLUSH_KEY] = $flushMessages; 
        // echo "<pre>";
        // var_dump($_SESSION[self::FLUSH_KEY]);
        // echo "</pre>";
  
    }



    public function setFlush($key, $message)
    {
        $_SESSION[self::FLUSH_KEY][$key]=[
            'remove'=> false,
            'value' => $message
        ];
    }


    public function getFlush($key)
    {
       return $_SESSION[self::FLUSH_KEY][$key]['value']??false;
    }


    public function set($key, $value)
    {
        $_SESSION[$key]=$value;
    }


    public function get($key)
    {
        return $_SESSION[$key] ?? false;
    }


    public function remove($key)
    {
        unset($_SESSION[$key]);
    }



    public function __destruct()
    {
        
        $flushMessages = $_SESSION[self::FLUSH_KEY] ?? [];

        foreach ($flushMessages as $key => &$flushMessage) 
        {

           if($flushMessage['remove'])
           {
               unset($flushMessages[$key]);
           }

        }

        $_SESSION[self::FLUSH_KEY] = $flushMessages;
    }










}