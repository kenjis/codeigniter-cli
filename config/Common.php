<?php
/**
 * Part of Cli for CodeIgniter
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/codeigniter-cli
 */

namespace Kenjis\CodeIgniter_Cli\_Config;

use Aura\Di\Config;
use Aura\Di\Container;

class Common extends Config
{
    private $commands = [
        'Seed', 'Migrate', 'Generate',
    ];

    public function define(Container $di)
    {
        $di->set('aura/project-kernel:logger', $di->newInstance('Monolog\Logger'));

        /* @var $ci \CI_Controller */
        $ci =& get_instance();

        foreach ($this->commands as $command) {
            $class = 'Kenjis\CodeIgniter_Cli\Command\\' . $command;
            $di->params[$class] = [
                'context' => $di->lazyGet('aura/cli-kernel:context'),
                'stdio' => $di->lazyGet('aura/cli-kernel:stdio'),
                'ci' => $ci,
            ];
        }

        $this->user_command_path = APPPATH . 'commands/';
        $this->registerUserCommandClasses($di, $ci);
    }

    private function registerUserCommandClasses($di, $ci)
    {
        foreach (glob($this->user_command_path . '*Command.php') as $file) {
            require_once $file;
            $classname = basename($file, '.php');
            if (! class_exists($classname)) {
                $this->stdio->errln(
                    '<<red>>No such class: ' . $classname . ' in ' . $file . '<<reset>>'
                );
                break;
            }
            
            $di->params[$classname] = array(
                'context' => $di->lazyGet('aura/cli-kernel:context'),
                'stdio' => $di->lazyGet('aura/cli-kernel:stdio'),
                'ci' => $ci,
            );
        }
    }

    public function modify(Container $di)
    {
        $this->modifyLogger($di);
        $this->modifyCliDispatcher($di);
        $this->modifyCliHelpService($di);
    }

    protected function modifyLogger(Container $di)
    {
        $project = $di->get('project');
        $mode = $project->getMode();
        $file = $project->getPath("tmp/log/{$mode}.log");

        $logger = $di->get('aura/project-kernel:logger');
        $logger->pushHandler($di->newInstance(
            'Monolog\Handler\StreamHandler',
            array(
                'stream' => $file,
            )
        ));
    }

    protected function modifyCliDispatcher(Container $di)
    {
        $context = $di->get('aura/cli-kernel:context');
        $stdio = $di->get('aura/cli-kernel:stdio');
        $logger = $di->get('aura/project-kernel:logger');
        $dispatcher = $di->get('aura/cli-kernel:dispatcher');
//        $dispatcher->setObject(
//            'hello',
//            function ($name = 'World') use ($context, $stdio, $logger) {
//                $stdio->outln("Hello {$name}!");
//                $logger->debug("Said hello to '{$name}'");
//            }
//        );

        foreach ($this->commands as $command) {
            $class = 'Kenjis\CodeIgniter_Cli\Command\\' . $command;
            $command_name = strtolower($command);
            $dispatcher->setObject(
                $command_name,
                $di->lazyNew($class)
            );
        }

        $this->registerUserCommands($di, $dispatcher);
    }

    private function registerUserCommands($di, $dispatcher)
    {
        foreach (glob($this->user_command_path . '*Command.php') as $file) {
            require_once $file;
            $classname = basename($file, '.php');
            if (! class_exists($classname)) {
                $this->stdio->errln(
                    '<<red>>No such class: ' . $classname . ' in ' . $file . '<<reset>>'
                );
                break;
            }
            
            $command_name = strtolower(basename($classname, 'Command'));
            $dispatcher->setObject(
                $command_name,
                $di->lazyNew($classname)
            );
        }
    }

    protected function modifyCliHelpService(Container $di)
    {
        $help_service = $di->get('aura/cli-kernel:help_service');

//        $help = $di->newInstance('Aura\Cli\Help');
//        $help_service->set('hello', function () use ($help) {
//            $help->setUsage(array('', '<noun>'));
//            $help->setSummary("A demonstration 'hello world' command.");
//            return $help;
//        });

        foreach ($this->commands as $command) {
            $class = 'Kenjis\CodeIgniter_Cli\Command\\' . $command . 'Help';
            $command_name = strtolower($command);
            $help_service->set(
                $command_name,
                $di->lazyNew($class)
            );
        }

        $this->registerUserCommandHelps($di, $help_service);
    }

    private function registerUserCommandHelps($di, $help_service)
    {
        foreach (glob($this->user_command_path . '*CommandHelp.php') as $file) {
            require_once $file;
            $classname = basename($file, '.php');
            if (! class_exists($classname)) {
                $this->stdio->errln(
                    '<<red>>No such class: ' . $classname . ' in ' . $file . '<<reset>>'
                );
                break;
            }
            
            $command_name = strtolower(basename($classname, 'CommandHelp'));
            $help_service->set(
                $command_name,
                $di->lazyNew($classname)
            );
        }
    }
}
