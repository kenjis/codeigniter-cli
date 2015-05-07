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
use Kenjis\CodeIgniter_Cli\UserConfig;

class Common extends Config
{
    /**
     * @var array list of system commands
     */
    private $commands = [
        'Seed', 'Migrate', 'Generate', 'Run',
    ];

    private $user_command_paths = [];

    public function define(Container $di)
    {
        $di->set('aura/project-kernel:logger', $di->newInstance('Monolog\Logger'));

        /* @var $ci \CI_Controller */
        $ci =& get_instance();

        // register system command classes
        foreach ($this->commands as $command) {
            $class = 'Kenjis\CodeIgniter_Cli\Command\\' . $command;
            $di->params[$class] = [
                'context' => $di->lazyGet('aura/cli-kernel:context'),
                'stdio' => $di->lazyGet('aura/cli-kernel:stdio'),
                'ci' => $ci,
            ];
        }

        $seeder_path = APPPATH . 'database/seeds/';
        $di->setter['Kenjis\CodeIgniter_Cli\Command\Seed']['setSeederPath']
            = $seeder_path;

        $this->user_command_paths = [ APPPATH . 'commands/' ];
        // register user command classes
        UserConfig::registerCommandClasses($di, $ci, $this->user_command_paths);
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
//        $context = $di->get('aura/cli-kernel:context');
//        $stdio = $di->get('aura/cli-kernel:stdio');
//        $logger = $di->get('aura/project-kernel:logger');
        $dispatcher = $di->get('aura/cli-kernel:dispatcher');

        // register system commands
        foreach ($this->commands as $command) {
            $class = 'Kenjis\CodeIgniter_Cli\Command\\' . $command;
            $command_name = strtolower($command);
            $dispatcher->setObject(
                $command_name,
                $di->lazyNew($class)
            );
        }

        // register user commands
        UserConfig::registerCommands($di, $dispatcher, $this->user_command_paths);
    }

    protected function modifyCliHelpService(Container $di)
    {
        $help_service = $di->get('aura/cli-kernel:help_service');

        // register system command helps
        foreach ($this->commands as $command) {
            $class = 'Kenjis\CodeIgniter_Cli\Command\\' . $command . 'Help';
            $command_name = strtolower($command);
            $help_service->set(
                $command_name,
                $di->lazyNew($class)
            );
        }

        // register user command helps
        UserConfig::registerCommandHelps(
            $di,
            $help_service,
            $this->user_command_paths
        );
    }
}
