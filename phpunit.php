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

require ROOTPATH . '/ci_instance.php';

/**
 * Fixture for testing
 */
$ci =& get_instance();

// switch database to SQLite for testing
$config = [
    'hostname' => 'sqlite:' . __DIR__ . '/tests/data/sqlite-database.db',
    'username' => '',
    'password' => '',
    'database' => '',
    'dbdriver' => 'PDO',
    'dbprefix' => '',
    'pconnect' => true,
    'db_debug' => true,
    'cache_on' => false,
    'cachedir' => '',
    'char_set' => 'utf8',
    'dbcollat' => 'utf8_general_ci',
    'swap_pre' => '',
    'autoinit' => true,
    'stricton' => false,
];
$ci->load->database($config);

// change migration config
$config = [
    'migration_enabled'     => true,
    'migration_type'        => 'timestamp',
    'migration_table'       => 'migrations',
    'migration_auto_latest' => false,
    'migration_version'     => 20150429110003,
    'migration_path'        => __DIR__ . '/tests/Fake/migrations/',
];
$ci->load->library('migration', $config);
