<?php
namespace aj\phpmvc;
use aj\phpmvc\db\Database;

class Application
{
    const EVENT_BEFORE_REQUEST  = "beforeRequest";
    const EVENT_AFTER_REQUEST   = "afterRequest";

    protected array $eventListeners = [];
    
    public static  Application $app;
    public static  string $ROOT_DIR;
    public string $layout="main";
    public View $view;
    public string $userClass;
    public Router $router;
    public Database $db;
    public ?UserModel $user;
    public Request $request;
    public Response $response;
    public Session $session;
    public ?Controller $controller=null;

    public function __construct($rootPath,array $config)
    {
        self::$app = $this;
        $this->userClass= $config['userClass'];
        self::$ROOT_DIR = $rootPath;
        $this->request  = new Request();
        $this->response = new Response();
        $this->session  = new Session();
        $this->router   = new Router($this->request,$this->response);
        $this->view     = new View();
        $this->db       = new Database($config['db']);


        $primaryValue   = $this->session->get('user');

        if($primaryValue)
        {
            $primaryKey = $this->userClass::primaryKey();
            $this->user = $this->userClass::findOne([$primaryKey => $primaryValue]);
        }else{
            $this->user = null;
        }

    }

    public function getController()
    {
       $this->controller;
    }

    public function setController(Controller $controller)
    {
        $this->controller= $controller;
    }

    public function run()
    {
       try {
           
           //Trigger EventListener 
            $this->triggerEvent(self::EVENT_BEFORE_REQUEST);

            echo $this->router->resolve();

       } catch (\Exception $e) {

            $this->response->setStatusCode($e->getCode());

           echo $this->view->renderView('_error',[
               'exception'=>$e
           ]);
       }
    }
    
    
    
    public function on($eventName,$callback)
    {
        $this->eventListeners[$eventName][] = $callback;
    }


    public function triggerEvent($eventName)
    {
        //
        $callbacks = $this->eventListeners[$eventName] ?? [];

        foreach ($callbacks as $callback) {
            call_user_func($callback);
        }
    }


    public function login(UserModel $user)
    {
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user',$primaryValue);

        return true;
    }

    public function logout()
    {
        $this->user = null;
        $this->session->remove('user');
    }

    public static function isGuest()
    {
        return !self::$app->user;
    }


}
