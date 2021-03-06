<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));
    return $logger;
};

// Customized logger: Wraps monolog adding time elapsed from beginning of the script (using $startTime from index.php)
$container['mylog'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], $settings['level']));

    $mylog = function($msg) use ($logger) {
        if (strpos($msg, "NEW") === 0) {
            $logger->info("----------------------------------------");
            $msg = trim(substr($msg, 3)); 
        }
        global $startTime;
        $timeSoFar = round(microtime(TRUE) - $startTime, 3);
        $logger->info($timeSoFar . " : " . $msg);
    };

    return $mylog;
};

// Database
$container['db'] = function ($c) {
    $db = $c['settings']['db'];
    $connectionString = "mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'] . ";charset=utf8mb4";

    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ];

    $pdo = new PDO($connectionString, $db['user'], $db['pass'], $options);

    return $pdo;
};


