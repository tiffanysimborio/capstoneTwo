<?php

class install
{
    private $connect;
    public $config;

    function __construct()
    {
        $this->mysql_structure = require 'mysql.php';
    }

    public function check_connect($host, $database, $username, $password)
    {
        $this->connect = mysqli_connect($host, $username, $password, $database);

        if(!$this->connect)
        {
            return false;
        }

        $check_db = $this->check_db($database);

        if(!$check_db)
        {
             return false;
        }

        $this->config['host'] 	  = $host;
        $this->config['database_user'] = $username;
        $this->config['database_password'] = $password;
        $this->config['database_name'] = $database;

        return true;
    }

    public function set_config()
    {
        $config_path  = 'config/';

		$config_db_file  = 'database.php';
		$config_db       = $this->read_config($config_path . $config_db_file);

		/* Edit Database Information */
		$config_db = str_replace('localhost', $this->config['host'], $config_db);
		$config_db = str_replace('database_user',$this->config['database_user'], $config_db);
		$config_db = str_replace('database_password', $this->config['database_password'], $config_db);
		$config_db = str_replace('database_name', $this->config['database_name'], $config_db);

		$this->write_config($config_db_file, $config_db);

        /* Application config file */
		$config_app_file  = 'application.php';
		$config_app       = $this->read_config($config_path . $config_app_file);

		/* Timezone */
		$config_app = str_replace('UTC', $_POST['timezone'], $config_app);

		/* Key */
		$config_app = str_replace('yourrandomkey', md5(serialize(time())), $config_app);

		$this->write_config($config_app_file, $config_app);

        /* Error config file */
        $config_error_file  = 'error.php';
        $config_error       = $this->read_config($config_path . $config_error_file);

        $this->write_config($config_error_file, $config_error);
    }

    public function read_config($file)
    {
    	if(!file_exists($file))
		{
			die('Sorry, we need a ' . $file . ' file to work with.');
		}

		return file_get_contents($file);
    }

    public function write_config($file, $content)
    {
        $path = '../app/application/config/site/';

    	if(is_writable(realpath($path)))
		{
			file_put_contents($path . $file, $content);
		}
		else
		{
			die('The config files in "app/application/config/site/" directory are not writable, you may have to config them by hand');
		}
    }

    public function check_requirements()
    {
        $errors = array();

        if(!extension_loaded('pdo'))
        {
            $errors[] = 'PDO extension not found.';
        }

        if(!extension_loaded('pdo_mysql'))
        {
            $errors[] = 'Mysql driver for pdo not found .';
        }

        if(!extension_loaded('mcrypt'))
        {
            $errors[] = 'Mcrypt extension not found.';
        }

        if(version_compare(PHP_VERSION, '5.3.0', '<'))
        {
            $errors[] = 'PHP too old for Laravel. PHP 5.3.0 or above is needed.';
        }

        return $errors;
    }

    public function create_tables()
    {
        foreach($this->mysql_structure as $query)
        {
            mysqli_query($this->connect, $query);
        }

        return true;
    }

    private function check_db($database)
    {
        $database_connect = mysqli_select_db($this->connect, $database);

        if($database_connect)
        {
            return $database_connect;
        }

        return false;
    }
}
