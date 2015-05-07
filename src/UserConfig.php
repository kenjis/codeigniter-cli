<?php
/**
 * Part of Cli for CodeIgniter
 *
 * @author     Kenji Suzuki <https://github.com/kenjis>
 * @license    MIT License
 * @copyright  2015 Kenji Suzuki
 * @link       https://github.com/kenjis/codeigniter-cli
 */

namespace Kenjis\CodeIgniter_Cli;

use Aura\Di\Container;

class UserConfig
{
    public static function registerCommandClasses(Container $di, $ci, array $paths)
    {
        foreach ($paths as $path) {
            foreach (glob($path . '*Command.php') as $file) {
                $classname = static::findClass($di, $file);
                if ($classname === '') {
                    break;
                }

                $di->params[$classname] = [
                    'context' => $di->lazyGet('aura/cli-kernel:context'),
                    'stdio'   => $di->lazyGet('aura/cli-kernel:stdio'),
                    'ci'      => $ci,
                ];
            }
        }
    }

    /**
     * @param string $file
     * @return string classname, if not found returns ''
     */
    protected static function findClass(Container $di, $file)
    {
        require_once $file;
        $classname = basename($file, '.php');
        if (! class_exists($classname)) {
            $stdio = $di->get('aura/cli-kernel:stdio');
            $stdio->errln(
                '<<red>>No such class: ' . $classname . ' in ' . $file . '<<reset>>'
            );
            return '';
        }
        return $classname;
    }

    public static function registerCommands(Container $di, $dispatcher, array $paths)
    {
        foreach ($paths as $path) {
            foreach (glob($path . '*Command.php') as $file) {
                $classname = static::findClass($di, $file);
                if ($classname === '') {
                    break;
                }

                $command_name = strtolower(basename($classname, 'Command'));
                $dispatcher->setObject(
                    $command_name,
                    $di->lazyNew($classname)
                );
            }
        }
    }

    public static function registerCommandHelps(Container $di, $help_service, array $paths)
    {
        foreach ($paths as $path) {
            foreach (glob($path . '*CommandHelp.php') as $file) {
                $classname = static::findClass($di, $file);
                if ($classname === '') {
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
}
