<?php
/**
 * Part of Cli for CodeIgniter
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/codeigniter-cli
 */

namespace Kenjis\CodeIgniter_Cli\Command;

use Aura\Cli\Stdio;
use Aura\Cli\Context;
use Aura\Cli\Status;
use CI_Controller;

class Seed extends Command
{
    private $seeder_path;

    public function __construct(Context $context, Stdio $stdio, CI_Controller $ci)
    {
        parent::__construct($context, $stdio, $ci);
    }

    /**
     * @param string $seeder_path directory of seeder files
     */
    public function setSeederPath($seeder_path)
    {
        $this->seeder_path = $seeder_path;
    }

    /**
     * @param string $class class name
     */
    public function __invoke($class = null)
    {
        $options =[
            'l',    // short flag -l, parameter is not allowed
            'list', // long option --list, parameter is not allowed
        ];
        $getopt = $this->context->getopt($options);
        $list = $getopt->get('-l', false) || $getopt->get('--list', false);

        if ($list) {
            $this->listSeederFiles();
            return;
        }

        if ($class === null) {
            $seeder_list = $this->findSeeder();
        } else {
            $seeder_list = [$this->seeder_path . $class . '.php'];
        }

        $this->runSeederList($seeder_list);
    }

    /**
     * run another seeder
     *
     * @param string $class class name
     */
    public function call($class)
    {
        $seeder_list = [$this->seeder_path . $class . '.php'];
        $this->runSeederList($seeder_list);
    }

    private function runSeederList($seeder_list)
    {
        foreach ($seeder_list as $file) {
            if (! is_readable($file)) {
                $this->stdio->errln('<<red>>Can\'t read: ' . $file . '<<reset>>');
                break;
            }
            require_once $file;
            $classname = basename($file, '.php');
            if (! class_exists($classname)) {
                $this->stdio->errln(
                    '<<red>>No such class: ' . $classname . ' in ' . $file . '<<reset>>'
                    . ' [' . __METHOD__ . ': line ' . __LINE__ . ']'
                );
                break;
            }
            $seeder = new $classname($this->context, $this->stdio, $this->ci);
            $this->runSeed($seeder);
            $this->stdio->outln('<<green>>Seeded: ' . $classname . '<<reset>>');
        }
    }

    private function listSeederFiles()
    {
        $seeder_list = $this->findSeeder();
        foreach ($seeder_list as $file) {
            if (is_readable($file)) {
                $this->stdio->outln('  ' . $file);
            }
        }
    }

    private function runSeed($seeder)
    {
        $seeder->run();
    }

    private function findSeeder()
    {
        $seeders = [];
        foreach (glob($this->seeder_path . '*.php') as $file) {
            $seeders[] = $file;
        }
        return $seeders;
    }
}
