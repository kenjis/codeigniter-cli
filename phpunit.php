<?php

error_reporting(E_ALL);

$autoloader = __DIR__ . '/vendor/autoload.php';
if (! file_exists($autoloader)) {
    echo "Composer autoloader not found: $autoloader" . PHP_EOL;
    echo "Please issue 'composer install' and try again." . PHP_EOL;
    exit(1);
}
require $autoloader;

/** @const ROOTPATH CodeIgniter project root directory */
define('ROOTPATH', realpath(__DIR__ . '/../../..') . '/');
chdir(ROOTPATH);

class_alias('Kenjis\CodeIgniter_Cli\Command\Command', 'Command');
class_alias('Kenjis\CodeIgniter_Cli\Command\Seed',    'Seeder');
class_alias('Aura\Cli\Help', 'Help');
