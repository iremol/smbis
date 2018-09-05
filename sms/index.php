<?php

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use sms\core\Config;
use sms\core\Request;
use sms\core\Router;
use sms\util\DependencyInjector;
use Monolog\Handler\FirePHPHandler;

require_once __DIR__ . '/vendor/autoload.php';

$config = new Config();

$dbConfig = $config->get('db');
//print_r($dbConfig);

$db_applicant = new PDO(
        "pgsql:host=localhost;dbname=sms;user=".$dbConfig['application']['user'].";password=".$dbConfig['application']['password'].";"
);
$db_admission = new PDO(
        "pgsql:host=localhost;dbname=sms;user=".$dbConfig['admission']['user'].";password=".$dbConfig['admission']['password'].";"
);
$db_human_res = new PDO(
        "pgsql:host=localhost;dbname=sms;user=".$dbConfig['human_res']['user'].";password=".$dbConfig['human_res']['password'].";"
);
$db_mgt = new PDO(
        "pgsql:host=localhost;dbname=sms;user=".$dbConfig['management']['user'].";password=".$dbConfig['management']['password'].";"
);

$db = ["db_applicant" => $db_applicant,"db_admission" => $db_admission,"db_human_res" => $db_human_res,"db_mgt"=>$db_mgt];

$loader = new Twig_Loader_Filesystem(__DIR__ . '/views');
//$view = new Twig_Environment($loader);
$view = new Twig_Environment($loader, ['debug'=>true]);
$view->addExtension(new Twig_Extension_Debug());

$log = new Logger('sms');
$logFile = $config->get('log');

$log->pushHandler(new StreamHandler($logFile, Logger::DEBUG));
$log->pushHandler(new FirePHPHandler());

$di = new DependencyInjector();
$di->set('PDO', $db);
$di->set('Utils\Config', $config);
$di->set('Twig_Environment', $view);
$di->set('Logger', $log);

$router = new Router($di);
$response = $router->route(new Request());
echo $response;


// echo $view->loadTemplate('bioinfo.twig')->render([]);