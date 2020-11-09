<?php
namespace aj\phpmvc\db;
use PDO;
use aj\phpmvc\Application;

class Database
{

    public \PDO $pdo;

    public function __construct(array $config)
    {
        $dsn        = $config['dsn']??'';
        $user       = $config['user']??'';
        $password   = $config['password']??'';

        $this->pdo = new PDO($dsn,$user,$password);
        $this->pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }


    public function applyMigrations()
    {

        $this->createMigrationsTable();

        $appliedMigrations  = $this->getAppliedMigrations();

        $newMigrations=[];
        $files              = scandir(Application::$ROOT_DIR.'/migrations');
        $toApplyMigrations  = array_diff($files,$appliedMigrations);

        foreach ($toApplyMigrations as $migration) {

            if($migration === "." || $migration === "..")
            {
                continue;
            }

            require_once Application::$ROOT_DIR.'/migrations/'.$migration;


            $className = pathinfo($migration,PATHINFO_FILENAME);
            $instances = new $className();
            
            // var_dump($className);
            // exit;
            $this->log("Applying Migration $migration");
            $instances->up();
            $this->log("Applied Migration $migration");
            $newMigrations[]=$migration;
        }

        if(!empty($newMigrations))
        {
            $this->saveMigrations($newMigrations);
        }else{
            $this->log("All Migrations are Applied!");
        }
        
    }

    public function createMigrationsTable()
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS migrations(
                id INT AUTO_INCREMENT PRIMARY KEY,
                migration VARCHAR(255),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
            ENGINE=INNODB;");
    }


    public function getAppliedMigrations()
    {
        $statement = $this->pdo->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }


    public function saveMigrations(array $migrations)
    {
        $arr = array_map(fn($m)=> "('$m')",$migrations);

        foreach ($arr as $key => $value) {

            $statement = $this->pdo->prepare("INSERT INTO migrations (migration) VALUES ($value)");
            $statement->execute();

        }
        // exit;
        // $str = implode(",",array_map(fn($m)=> "('$m')",$migrations));
        // die($str);
       

    }

    protected function log($message)
    {
        echo '['.date('Y-m-d H:i:s').'] - '.$message.PHP_EOL;
    }


    public function prepare($sql)
    {
        return $this->pdo->prepare($sql);
    }




}